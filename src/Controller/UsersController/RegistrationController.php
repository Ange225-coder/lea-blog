<?php

    namespace App\Controller\UsersController;

    use App\Entity\Tables\Users\Users;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Entity\FormFields\Users\RegistrationFields;
    use App\FormsTypes\Users\RegistrationType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Session\SessionInterface;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Security\Core\Exception\AuthenticationException;
    use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
    use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
    use App\Security\UsersAuthenticator;

    class RegistrationController extends AbstractController
    {
        #[Route(path: '/user/registration', name: 'user_registration')]
        public function userRegistration(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, UserPasswordHasherInterface $passwordHasher, UserAuthenticatorInterface $authenticator, UsersAuthenticator $formAuthenticator): Response
        {
            $userEntity = new Users();

            $registrationFields = new RegistrationFields();

            $registrationType = $this->createForm(RegistrationType::class, $registrationFields);

            $registrationType->handleRequest($request);

            if($registrationType->isSubmitted() && $registrationType->isValid()) {
                $formData = $registrationType->getData();

                //$em = $doctrine->getManager();

                $existingPseudo = $entityManager->getRepository(Users::class)->findOneBy([
                    'pseudonyme' => $formData->getPseudonyme(),
                ]);

                $existingEmail = $entityManager->getRepository(Users::class)->findOneBy([
                    'email' => $formData->getEmail(),
                ]);

                if($existingPseudo) {
                    $registrationType->get('pseudonyme')->addError(new FormError('Ce pseudonyme est déjà utilisé'));
                }

                if($existingEmail) {
                    $registrationType->get('email')->addError(new FormError('Cet email existe déjà'));
                }

                if($registrationType->getErrors(true)->count() > 0) {
                    return $this->render('users/registration.html.twig', [
                        'registration' => $registrationType->createView(),
                    ]);
                }

                $userEntity->setPseudonyme($formData->getPseudonyme());
                $userEntity->setEmail($formData->getEmail());
                $userEntity->setPassword($passwordHasher->hashPassword($userEntity, $formData->getPassword()));

                $entityManager->persist($userEntity);
                $entityManager->flush();

                $session->set('user_id', $userEntity->getId());

                //authenticate user automatically
                try {
                    $authenticator->authenticateUser($userEntity, $formAuthenticator, $request);

                    return $this->redirectToRoute('user_dashboard');
                }
                catch (CustomUserMessageAuthenticationException $e) {
                    $this->addFlash('error', $e->getMessage());

                    return $this->redirectToRoute('user_registration');
                }
                catch (AuthenticationException $e) {
                    $this->addFlash('error', 'Une erreur est survenu');

                    return $this->redirectToRoute('user_registration');
                }

                //return $this->redirectToRoute('user_dashboard');
            }

            return $this->render('users/registration.html.twig', [
                'registration' => $registrationType->createView(),
            ]);
        }
    }
<?php

    namespace App\Controller\AdminsController;

    use App\Entity\Tables\Admins\Admins;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use App\Entity\FormFields\Admins\RegistrationFields;
    use App\FormsTypes\Admins\RegistrationTypes;
    use Symfony\Component\Security\Core\Exception\AuthenticationException;
    use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
    use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
    use App\Security\AdminsAuthenticator;

    class RegistrationController extends AbstractController
    {
        #[Route(path: '/admin/registration', name: 'admin_registration')]
        public function adminRegistration(Request $request, PersistenceRegistry $doctrine, UserPasswordHasherInterface $passwordHasher, UserAuthenticatorInterface $authenticator, AdminsAuthenticator $formAuthenticator): Response
        {
            $adminEntity = new Admins();

            $em = $doctrine->getManager();

            $registrationFields = new RegistrationFields();

            $registrationTypes = $this->createForm(RegistrationTypes::class, $registrationFields);

            $registrationTypes->handleRequest($request);

            if($registrationTypes->isSubmitted() && $registrationTypes->isValid()) {
                $formData = $registrationTypes->getData();

                $existingUsername = $em->getRepository(Admins::class)->findOneBy([
                    'username' => $formData->getUsername(),
                ]);

                $existingEmail = $em->getRepository(Admins::class)->findOneBy([
                    'email' => $formData->getEmail(),
                ]);

                if($existingUsername) {
                    $registrationTypes->get('username')->addError(new FormError('Un administrateur utilise déjà ce nom'));
                }

                if($existingEmail) {
                    $registrationTypes->get('email')->addError(new FormError('Un administrateur utilise déjà cet email'));
                }

                if($registrationTypes->getErrors(true)->count() > 0) {
                    return $this->render('admins/registration.html.twig', [
                        'registration' => $registrationTypes->createView(),
                    ]);
                }

                $adminEntity->setUsername($formData->getUsername());
                $adminEntity->setEmail($formData->getEmail());
                $adminEntity->setPassword($passwordHasher->hashPassword($adminEntity, $formData->getPassword()));

                $em->persist($adminEntity);
                $em->flush();

                //authenticate user here
                try {
                    $authenticator->authenticateUser($adminEntity, $formAuthenticator, $request);

                    return $this->redirectToRoute('admin_dashboard');
                }
                catch(CustomUserMessageAuthenticationException $e) {
                    $this->addFlash('error', $e->getMessage());

                    return $this->redirectToRoute('admin_registration');
                }
                catch (AuthenticationException) {
                    $this->addFlash('error', 'Une erreur est survenue');

                    return $this->redirectToRoute('admin_registration');
                }
            }

            return $this->render('admins/registration.html.twig', [
                'registration' => $registrationTypes->createView(),
            ]);
        }
    }
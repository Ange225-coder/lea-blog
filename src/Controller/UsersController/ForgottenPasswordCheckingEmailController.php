<?php

    namespace App\Controller\UsersController;

    use App\Entity\Tables\Users\Users;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use App\Entity\FormFields\Users\ForgottenPasswordEmailCheckingFields;
    use App\FormsTypes\Users\ForgottenPasswordEmailCheckingTypes;
    use Symfony\Component\HttpFoundation\Session\SessionInterface;

    class ForgottenPasswordCheckingEmailController extends AbstractController
    {
        #[Route(path: '/user/forgotPass/emailChecking', name: 'user_forgot_pass')]
        public function forgottenPassCheckingEmail(Request $request, PersistenceRegistry $doctrine, SessionInterface $session): Response
        {
            $em = $doctrine->getManager();

            $checkingEmailFields = new ForgottenPasswordEmailCheckingFields();

            $checkingEmailTypes = $this->createForm(ForgottenPasswordEmailCheckingTypes::class, $checkingEmailFields);

            $checkingEmailTypes->handleRequest($request);

            if($checkingEmailTypes->isSubmitted() && $checkingEmailTypes->isValid()) {
                $formData = $checkingEmailTypes->getData();

                $emailEntered = $em->getRepository(Users::class)->findOneBy([
                    'email' => $formData->getEmail(),
                ]);

                if(!$emailEntered)  {
                    $checkingEmailTypes->get('email')->addError(new FormError('Vérifier le lien de confirmation envoyé à votre email pour changer de mot de passe'));
                }
                else {
                    $session->set('user_id', $emailEntered->getId());

                    return $this->redirectToRoute('user_forgot_pass_change_pass');
                }
            }

            return $this->render('users/forgottenPasswordCheckingEmail.html.twig', [
                'checkingEmail' => $checkingEmailTypes->createView(),
            ]);
        }
    }
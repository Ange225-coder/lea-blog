<?php

    namespace App\Controller\UsersController;

    use App\Entity\Tables\Users\Users;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use App\Entity\FormFields\Users\ForgottenPasswordChangePassFields;
    use App\FormsTypes\Users\ForgottenPasswordChangePassTypes;
    use Symfony\Component\HttpFoundation\Session\SessionInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

    class ForgottenPasswordChangePassController extends AbstractController
    {
        #[Route(path: '/user/forgotPass/changePass', name: 'user_forgot_pass_change_pass')]
        public function forgottenPasswordChangePass(Request $request, PersistenceRegistry $doctrine, UserPasswordHasherInterface $passwordHasher, SessionInterface $session): Response
        {
            $userId = $session->get('user_id');

            $em = $doctrine->getManager();

            $changePassFields = new ForgottenPasswordChangePassFields();

            $changePassTypes = $this->createForm(ForgottenPasswordChangePassTypes::class, $changePassFields);

            $changePassTypes->handleRequest($request);

            if($changePassTypes->isSubmitted() && $changePassTypes->isValid()) {
                $formData = $changePassTypes->getData();

                $newPassword = $formData->getNewPassword();
                $repeatNewPassword = $formData->getRepeatNewPassword();

                $user = $em->getRepository(Users::class)->find($userId);

                if($newPassword === $repeatNewPassword) {
                    $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
                    $em->flush();

                    return $this->redirectToRoute('user_login');
                }
                else {
                    $changePassTypes->get('repeatNewPassword')->addError(New FormError('Les mots de passes ne correspondent pas'));
                }
            }

            return $this->render('users/forgottenPasswordChangePass.html.twig', [
                'changePass' => $changePassTypes->createView(),
                'userId' => $userId,
            ]);
        }

    }
<?php

    namespace App\Controller\UsersController;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Entity\Tables\Users\Users;
    use App\Entity\FormFields\Users\Settings\UpdatePseudonymeFields;
    use App\Entity\FormFields\Users\Settings\UpdateEmailFields;
    use App\Entity\FormFields\Users\Settings\UpdatePasswordFields;
    use App\FormsTypes\Users\Settings\UpdatePseudonymeTypes;
    use App\FormsTypes\Users\Settings\UpdateEmailTypes;
    use App\FormsTypes\Users\Settings\UpdatePasswordTypes;
    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

    class SettingsControllers extends AbstractController
    {
        #[Route(path: '/user/settings/{id}', name: 'user_settings')]
        //#[IsGranted('ROLE_USER')]
        public function userSettings($id, Request $request, UserPasswordHasherInterface $passwordHasher, PersistenceRegistry $doctrine, TokenStorageInterface $tokenStorage): Response
        {
            $em = $doctrine->getManager();

            $updatePseudoFields = new UpdatePseudonymeFields();
            $updateEmailFields = new UpdateEmailFields();
            $updatePasswordFields = new UpdatePasswordFields();

            $updatePseudonymeType = $this->createForm(UpdatePseudonymeTypes::class, $updatePseudoFields);
            $updateEmailType = $this->createForm(UpdateEmailTypes::class, $updateEmailFields);
            $updatePasswordType = $this->createForm(UpdatePasswordTypes::class, $updatePasswordFields);

            $updatePseudonymeType->handleRequest($request);
            $updateEmailType->handleRequest($request);
            $updatePasswordType->handleRequest($request);


            //pseudo updating manager
            if($updatePseudonymeType->isSubmitted() && $updatePseudonymeType->isValid()) {
                $formData = $updatePseudonymeType->getData();

                $user = $em->getRepository(Users::class)->find($id);
                $newPseudo = $formData->getNewPseudonyme();

                //checking pseudo entered
                $existingPseudo = $em->getRepository(Users::class)->findOneBy([
                    'pseudonyme' => $newPseudo,
                ]);

                if($existingPseudo) {
                    $updatePseudonymeType->get('newPseudonyme')->addError(new FormError('Ce pseudonyme est déjà utilisé'));
                }

                if($updatePseudonymeType->getErrors(true)->count() > 0) {
                    return $this->render('users/settings.html.twig', [
                        'updatePseudo' => $updatePseudonymeType->createView(),
                        'updateEmail' => $updateEmailType->createView(),
                        'updatePassword' => $updatePasswordType->createView(),
                    ]);
                }

                $user->setPseudonyme($newPseudo);
                $em->flush();

                $tokenStorage->setToken(null);

                return $this->redirectToRoute('home_page');
            }


            //email updating manager
            if($updateEmailType->isSubmitted() && $updateEmailType->isValid()) {
                $formData = $updateEmailType->getData();

                $user = $em->getRepository(Users::class)->find($id);
                $newEmail = $formData->getNewEmail();

                $existingEmail = $em->getRepository(Users::class)->findOneBy([
                    'email' => $newEmail,
                ]);

                if($existingEmail) {
                    $updateEmailType->get('newEmail')->addError(new FormError('Cet email existe déjà'));
                }

                if($updateEmailType->getErrors(true)->count() > 0) {
                    return $this->render('users/settings.html.twig', [
                        'updatePseudo' => $updatePseudonymeType->createView(),
                        'updateEmail' => $updateEmailType->createView(),
                        'updatePassword' => $updatePasswordType->createView(),
                    ]);
                }

                $user->setEmail($newEmail);
                $em->flush();

                //$this->addFlash('success', 'Mise à jour effectuée avec success');

                return $this->redirectToRoute('home_page');
            }


            //password  updating manager
            if($updatePasswordType->isSubmitted() && $updatePasswordType->isValid()) {
                $formData = $updatePasswordType->getData();

                $user = $em->getRepository(Users::class)->find($id);

                $currentPassword = $formData->getCurrentPassword();
                $newPassword = $formData->getNewPassword();
                $repeatPassword = $formData->getRepeatNewPassword();

                if($passwordHasher->isPasswordValid($user, $currentPassword)) {

                    if($newPassword === $repeatPassword) {
                        $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
                        $em->flush();

                        $tokenStorage->setToken(null);

                        return $this->redirectToRoute('home_page');
                    }
                    else {
                        $updatePasswordType->get('repeatNewPassword')->addError(new FormError('Les mot de passe ne correspondent pas'));
                    }
                }
                else {
                    $updatePasswordType->get('currentPassword')->addError(new FormError('Mot de passe incorrect, mise à jour impossible'));
                }

                if($updatePasswordType->getErrors(true)->count() > 0) {
                    return $this->render('users/settings.html.twig', [
                        'updatePseudo' => $updatePseudonymeType->createView(),
                        'updateEmail' => $updateEmailType->createView(),
                        'updatePassword' => $updatePasswordType->createView(),
                    ]);
                }
            }


            return $this->render('users/settings.html.twig', [
                'updatePseudo' => $updatePseudonymeType->createView(),
                'updateEmail' => $updateEmailType->createView(),
                'updatePassword' => $updatePasswordType->createView(),
            ]);
        }
    }
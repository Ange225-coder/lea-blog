<?php

    namespace App\Controller\AdminsController;

    use App\Entity\FormFields\Admins\DeletionsFields;
    use App\Entity\Tables\Admins\Admins;
    use App\Entity\Tables\Users\Users;
    use App\FormsTypes\Admins\DeletionsTypes;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class DeleteUserController extends AbstractController
    {
        #[Route(path: '/admin/user/deletion/{userId}', name: 'user_deletion')]
        #[IsGranted('ROLE_ADMIN')]
        public function deleteUser($userId, Request $request, UserPasswordHasherInterface $passwordHasher, PersistenceRegistry $doctrine): Response
        {
            //get user to deleted
            $em = $doctrine->getManager();
            $user = $em->getRepository(Users::class)->find($userId);

            $userDeletionFields = new DeletionsFields();

            $userDeletionTypes = $this->createForm(DeletionsTypes::class, $userDeletionFields);

            $userDeletionTypes->handleRequest($request);

            if($userDeletionTypes->isSubmitted() && $userDeletionTypes->isValid()) {
                $formData = $userDeletionTypes->getData();

                $adminLogged = $em->getRepository(Admins::class)->findOneBy([
                    'username' => $this->getUser()->getUserIdentifier(),
                ]);

                if($passwordHasher->isPasswordValid($adminLogged, $formData->getPassword())) {
                    $em->remove($user);
                    $em->flush();

                    return $this->redirectToRoute('admin_user_list');
                }
                else {
                    $userDeletionTypes->get('password')->addError(new FormError('Suppression impossible mot de passe incorrect'));
                }
            }

            return $this->render('admins/userDeletion.html.twig', [
                'userDeletion' => $userDeletionTypes->createView(),
                'user' => $user,
            ]);
        }
    }
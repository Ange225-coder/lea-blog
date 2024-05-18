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
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class DeleteUserController extends AbstractController
    {
        #[Route(path: '/admin/user/deletion/{userId}', name: 'user_deletion')]
        //#[IsGranted('ROLE_ADMIN')]
        public function deleteUser($userId, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
        {
            //get user to deleted
            //$em = $doctrine->getManager();
            $user = $entityManager->getRepository(Users::class)->find($userId);

            if(!$user) {
                throw $this->createNotFoundException('Utilisateur avec l\'identifiant '.$userId.' est introuvable');
            }

            $userDeletionFields = new DeletionsFields();

            $userDeletionTypes = $this->createForm(DeletionsTypes::class, $userDeletionFields);

            $userDeletionTypes->handleRequest($request);

            if($userDeletionTypes->isSubmitted() && $userDeletionTypes->isValid()) {
                $formData = $userDeletionTypes->getData();

                $adminLogged = $entityManager->getRepository(Admins::class)->findOneBy([
                    'username' => $this->getUser()->getUserIdentifier(),
                ]);

                if($passwordHasher->isPasswordValid($adminLogged, $formData->getPassword())) {
                    $entityManager->remove($user);
                    $entityManager->flush();

                    $this->addFlash('deletion_success', 'Un utilisateur vient d\'être retiré de la base de données');

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
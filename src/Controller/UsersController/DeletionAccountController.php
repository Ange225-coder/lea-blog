<?php

    namespace App\Controller\UsersController;

    use App\Entity\Tables\Users\Users;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use App\FormsTypes\Users\Settings\DeletionAccountTypes;
    use App\Entity\FormFields\Users\Settings\DeletionAccountFields;
    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

    class DeletionAccountController extends AbstractController
    {
        #[Route(path: '/user/settings/deletion/{id}', name: 'user_delete_account')]
        #[IsGranted('ROLE_USER')]
        public function deletionAccount($id, Request $request, PersistenceRegistry $doctrine, UserPasswordHasherInterface $passwordHasher, TokenStorageInterface $tokenStorage): Response
        {
            $em = $doctrine->getManager();

            $delAccountFields = new DeletionAccountFields();

            $delAccountTypes = $this->createForm(DeletionAccountTypes::class, $delAccountFields);

            $delAccountTypes->handleRequest($request);

            if($delAccountTypes->isSubmitted() && $delAccountTypes->isValid()) {
                $formData = $delAccountTypes->getData();

                $user = $em->getRepository(Users::class)->find($id);

                if($passwordHasher->isPasswordValid($user, $formData->getPassword())) {
                    $em->remove($user);
                    $em->flush();

                    $tokenStorage->setToken(null);

                    return $this->redirectToRoute('home_page');
                }
                else {
                    $delAccountTypes->get('password')->addError(new FormError('Mot de passe incorrect, impossible de supprimer le compte'));
                }
            }

            return $this->render('users/deleteAccount.html.twig', [
                'deletion' => $delAccountTypes->createView(),
            ]);
        }
    }
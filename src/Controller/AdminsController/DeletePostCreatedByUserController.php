<?php

    namespace App\Controller\AdminsController;

    use App\Entity\Tables\Admins\Admins;
    use App\Entity\Tables\Users\Posts;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Entity\FormFields\Admins\DeletionsFields;
    use App\FormsTypes\Admins\DeletionsTypes;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class DeletePostCreatedByUserController extends AbstractController
    {
        #[Route(path: '/admin/userDetails/postDeletion/{postId}', name: 'user_details_post_deletion')]
        #[IsGranted('ROLE_ADMIN')]
        public function deletePostCreatedByUser($postId, Request $request, PersistenceRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
        {
            $em = $doctrine->getManager();

            //get post to deleted
            $post = $em->getRepository(Posts::class)->find($postId);

            $deletionFields = new DeletionsFields();

            $deletionTypes = $this->createForm(DeletionsTypes::class, $deletionFields);

            $deletionTypes->handleRequest($request);

            if($deletionTypes->isSubmitted() && $deletionTypes->isValid()) {
                $formData = $deletionTypes->getData();

                $adminLogged = $em->getRepository(Admins::class)->findOneBy([
                    'username' => $this->getUser()->getUserIdentifier(),
                ]);

                if($passwordHasher->isPasswordValid($adminLogged, $formData->getPassword())) {
                    $em->remove($post);
                    $em->flush();

                    return $this->redirectToRoute('admin_home');
                }
                else {
                    $deletionTypes->get('password')->addError(new FormError('Suppression impossible mot de passe incorrect'));
                }
            }

            return $this->render('admins/deletionPost.html.twig', [
                'deletion' => $deletionTypes->createView(),
                'post' => $post
            ]);
        }
    }
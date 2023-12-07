<?php

    namespace App\Controller\UsersController;

    use App\Entity\Tables\Users\Posts;
    use App\Entity\Tables\Users\Users;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use App\Entity\FormFields\Users\DeletePostFields;
    use App\FormsTypes\Users\DeletePostTypes;

    class DeletePostController extends AbstractController
    {
        #[Route(path: '/user/post/deletion/{postId}', name: 'user_post_deletion')]
        public function deletePost($postId, Request $request, PersistenceRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
        {
            //get post to deleted here
            $em = $doctrine->getManager();
            $post = $em->getRepository(Posts::class)->find($postId);

            $deletionFields = new DeletePostFields();

            $deletionTypes = $this->createForm(DeletePostTypes::class, $deletionFields);

            $deletionTypes->handleRequest($request);

            if($deletionTypes->isSubmitted() && $deletionTypes->isValid()) {
                $formData = $deletionTypes->getData();

                $userLogged = $em->getRepository(Users::class)->findOneBy([
                    'pseudonyme' => $this->getUser()->getUserIdentifier(),
                ]);

                if($passwordHasher->isPasswordValid($userLogged, $formData->getPassword())) {
                    $em->remove($post);
                    $em->flush();

                    return $this->redirectToRoute('home_page');
                }
                else {
                    $deletionTypes->get('password')->addError(new FormError('Impossible de supprimer le post mot de passe incorrect'));
                }
            }

            return $this->render('users/deletionPost.html.twig', [
                'deletion' => $deletionTypes->createView(),
                'post' => $post,
            ]);
        }
    }
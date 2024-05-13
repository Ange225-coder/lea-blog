<?php

    namespace App\Controller\AdminsController;

    use App\Entity\Tables\Users\Comments;
    use App\Entity\Tables\Users\Posts;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\ORM\EntityManagerInterface;

    class AdminPostDetailController extends AbstractController
    {
        #[Route(path: '/admin/post/details/{postId}', name: 'admin_post_details')]
        public function postDetail($postId, EntityManagerInterface $entityManager): Response
        {
            //$em = $doctrine->getManager();
            $post = $entityManager->getRepository(Posts::class)->find($postId);

            if(!$post) {
                throw $this->createNotFoundException('L\'identifiant '. $postId .' n\'est pas trouvé');
            }

            //get all comments associated with this post
            $comments = $entityManager->getRepository(Comments::class)->findBy(
                ['post' => $post->getTitle()],
                ['commentDate' => 'DESC']
            );

            return $this->render('admins/postDetails.html.twig', [
                'post' => $post,
                'comments' => $comments,
            ]);
        }
    }
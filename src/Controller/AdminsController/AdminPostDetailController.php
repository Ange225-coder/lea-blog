<?php

    namespace App\Controller\AdminsController;

    use App\Entity\Tables\Users\Comments;
    use App\Entity\Tables\Users\Posts;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;

    class AdminPostDetailController extends AbstractController
    {
        #[Route(path: '/admin/post/details/{postId}', name: 'admin_post_details')]
        public function postDetail($postId, PersistenceRegistry $doctrine): Response
        {
            $em = $doctrine->getManager();
            $post = $em->getRepository(Posts::class)->find($postId);

            //get all comments associated with this post
            $comments = $em->getRepository(Comments::class)->findBy(
                ['post' => $post->getTitle()],
                ['commentDate' => 'DESC']
            );

            return $this->render('admins/postDetails.html.twig', [
                'post' => $post,
                'comments' => $comments,
            ]);
        }
    }
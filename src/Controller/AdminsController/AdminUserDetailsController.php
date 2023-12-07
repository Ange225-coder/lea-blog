<?php

    namespace App\Controller\AdminsController;

    use App\Entity\Tables\Users\Comments;
    use App\Entity\Tables\Users\Posts;
    use App\Entity\Tables\Users\Users;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AdminUserDetailsController extends AbstractController
    {
        #[Route(path: '/admin/userDetails/{userId}', name: 'admin_user_details')]
        #[IsGranted('ROLE_ADMIN')]
        public function userDetails($userId, PersistenceRegistry $doctrine): Response
        {
            //get all datas about user
            $em = $doctrine->getManager();
            $user = $em->getRepository(Users::class)->find($userId);

            //get posts created by this user
            $postsCreated = $em->getRepository(Posts::class)->findBy(
                ['author' => $user->getPseudonyme()],
            );

            //get comments posted by this user
            $commentsPosted = $em->getRepository(Comments::class)->findBy(
                ['author' => $user->getPseudonyme()]
            );

            return $this->render('admins/userDetails.html.twig', [
                'user' => $user,
                'posts' => $postsCreated,
                'comments' => $commentsPosted,
            ]);
        }
    }
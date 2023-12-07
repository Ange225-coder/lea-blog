<?php

    namespace App\Controller\AdminsController;

    use App\Entity\Tables\Users\Posts;
    use App\Entity\Tables\Users\Users;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AdminsShowPostsCreatedByAUserController extends AbstractController
    {
        #[Route(path: '/admin/userDetails/postsList/{userId}', name: 'admin_user_details_post_list')]
        #[IsGranted('ROLE_ADMIN')]
        public function showPostsCreatedByUser($userId, PersistenceRegistry $doctrine): Response
        {
            //get user
            $em = $doctrine->getManager();
            $user = $em->getRepository(Users::class)->find($userId);

            //get post created by this user
            $postsCreated = $em->getRepository(Posts::class)->findBy(
                ['author' => $user->getPseudonyme()],
                ['publicationDate' => 'DESC']
            );

            return $this->render('admins/userDetailsPostCreated.html.twig', [
                'posts' => $postsCreated,
            ]);
        }
    }
<?php

    namespace App\Controller\AdminsController;

    use App\Entity\Tables\Users\Comments;
    use App\Entity\Tables\Users\Users;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AdminShowCommentsPostedByAUserController extends AbstractController
    {
        #[Route(path: '/admin/userDetails/commentsList/{userId}', name: 'user_details_comments_list')]
        #[IsGranted('ROLE_ADMIN')]
        public function showCommentsPostedByAUser($userId, PersistenceRegistry $doctrine): Response
        {
            $em = $doctrine->getManager();
            $user = $em->getRepository(Users::class)->find($userId);

            //get comments posted by this user
            $commentPosted = $em->getRepository(Comments::class)->findBy(
                ['author' => $user->getPseudonyme()],
                ['commentDate' => 'DESC']
            );

            return $this->render('admins/userDetailsShowCommentsPosted.html.twig', [
                'comments' => $commentPosted,
            ]);
        }
    }
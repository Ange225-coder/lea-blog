<?php

    namespace App\Controller\AdminsController;

    use App\Entity\Tables\Users\Comments;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;

    class CommentsListController extends AbstractController
    {
        #[Route(path: '/admin/comments/list', name: 'admin_comments_list')]
        public function commentsList(PersistenceRegistry $doctrine): Response
        {
            $em = $doctrine->getManager();
            $commentsList = $em->getRepository(Comments::class)->findBy(
                [],
                ['commentDate' => 'DESC']
            );

            return $this->render('admins/commentsList.html.twig', [
                'commentsList' => $commentsList,
            ]);
        }
    }
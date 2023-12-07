<?php

    namespace App\Controller\AdminsController;

    use App\Entity\Tables\Users\Users;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use Knp\Component\Pager\PaginatorInterface;
    use Symfony\Component\HttpFoundation\Request;

    class AdminUsersListController extends AbstractController
    {
        #[Route(path: '/admin/userList', name: 'admin_user_list')]
        #[IsGranted('ROLE_ADMIN')]
        public function usersList(PersistenceRegistry $doctrine, PaginatorInterface $paginator, Request $request): Response
        {
            $em = $doctrine->getManager();

            //this line is for display only number of user registered
            $allUsers = $em->getRepository(Users::class)->findAll();

            $usersList = $em->getRepository(Users::class)->createQueryBuilder('u')
                ->orderBy('u.id', 'DESC')
                ->getQuery();


            $pagination = $paginator->paginate(
                $usersList,
                $request->query->getInt('page', 1),
                4,
            );

            return $this->render('admins/usersList.html.twig', [
                'usersList' => $pagination,
                'allUsers' => $allUsers,
            ]);
        }
    }
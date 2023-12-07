<?php

    namespace App\Controller\AdminsController;

    use App\Entity\Tables\Users\Posts;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use Knp\Component\Pager\PaginatorInterface;
    use Symfony\Component\HttpFoundation\Request;

    class AdminPostsListController extends AbstractController
    {
        #[Route(path: '/admin/posts/list', name: 'posts_list')]
        #[IsGranted('ROLE_ADMIN')]
        public function postsList(PersistenceRegistry $doctrine, PaginatorInterface $paginator, Request $request): Response
        {
            $em = $doctrine->getManager();

            $postsCounter = $em->getRepository(Posts::class)->findAll();

            $postsList = $em
                ->getRepository(Posts::class)
                ->createQueryBuilder('p')
                ->orderBy('p.publicationDate', 'DESC')
                ->getQuery()
            ;

            $pagination = $paginator->paginate($postsList, $request->query->getInt('page', 1), 7);

            return $this->render('admins/postsList.html.twig', [
                'postsList' => $pagination,
                'postsCounter' => $postsCounter,
            ]);
        }
    }
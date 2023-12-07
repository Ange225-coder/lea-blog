<?php

    namespace App\Controller\HomeController;

    use App\Entity\Tables\Users\Posts;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Knp\Component\Pager\PaginatorInterface;
    use Symfony\Component\HttpFoundation\Request;

    class IndexController extends AbstractController
    {
        #[Route(path: '/', name: 'home_page')]
        public function index(PersistenceRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
        {
            $em = $doctrine->getManager();
            $query = $em->getRepository(Posts::class)->createQueryBuilder('p')
                ->orderBy('p.id', 'DESC')
                ->getQuery();

            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                3,
            );

            return $this->render('index.html.twig', [
                'postsList' => $pagination,
            ]);
        }
    }
<?php

    namespace App\Controller\UsersController;

    use App\Entity\Tables\Users\Posts;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use App\Entity\FormFields\Users\SearchBarFields;
    use App\FormsTypes\Users\SearchBarTypes;

    class PostSearchesController extends AbstractController
    {
        #[Route(path: '/user/posts/searches', name: 'user_post_searches')]
        public function postSearches(Request $request, PersistenceRegistry $doctrine): Response
        {
            $searchFields = new SearchBarFields();

            $searchTypes = $this->createForm(SearchBarTypes::class, $searchFields);

            $postsList = [];

            $searchTypes->handleRequest($request);

            $formData = $searchTypes->getData();

            if($searchTypes->isSubmitted() && $searchTypes->isValid()) {

                $em = $doctrine->getManager();

                $postsList = $em
                    ->getRepository(Posts::class)
                    ->createQueryBuilder('p')
                    ->where('p.title LIKE :title')
                    ->setParameter('title', '%' . $formData->getKeyword() . '%')
                    ->orderBy('p.publicationDate', 'DESC')
                    ->getQuery()
                    ->getResult()
                ;
            }


            return $this->render('users/postSearches.html.twig', [
                'searches' => $searchTypes->createView(),
                'postsList' => $postsList,
                'keyword' => $formData->getKeyword(),
            ]);
        }
    }
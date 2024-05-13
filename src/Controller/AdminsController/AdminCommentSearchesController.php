<?php

    namespace App\Controller\AdminsController;

    use App\Entity\Tables\Users\Comments;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Entity\FormFields\Users\SearchBarFields;
    use App\FormsTypes\Users\SearchBarTypes;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AdminCommentSearchesController extends AbstractController
    {
        #[Route(path: '/admin/comment/searches', name: 'admin_comment_searches')]
        #[IsGranted('ROLE_ADMIN')]
        public function commentSearches(EntityManagerInterface $entityManager, Request $request): Response
        {
            $comSearchFields = new SearchBarFields();

            $comSearchTypes = $this->createForm(SearchBarTypes::class, $comSearchFields);

            $formData = $comSearchTypes->getData();

            $comments = [];

            $comSearchTypes->handleRequest($request);

            if($comSearchTypes->isSubmitted() && $comSearchTypes->isValid()) {
                //$em = $doctrine->getManager();

                $comments = $entityManager
                    ->getRepository(Comments::class)
                    ->createQueryBuilder('c')
                    ->where('c.post LIKE :post')
                    ->setParameter('post', '%' . $formData->getKeyword() . '%')
                    ->getQuery()
                    ->getResult()
                ;
            }

            return $this->render('admins/commentSearches.html.twig', [
                'comSearches' => $comSearchTypes->createView(),
                'coms' => $comments,
                'keyword' => $formData->getKeyword(),
            ]);
        }
    }
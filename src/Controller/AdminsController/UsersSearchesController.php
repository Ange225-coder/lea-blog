<?php

    namespace App\Controller\AdminsController;

    use App\Entity\Tables\Users\Users;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Entity\FormFields\Users\SearchBarFields;
    use App\FormsTypes\Users\SearchBarTypes;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class UsersSearchesController extends AbstractController
    {
        #[Route(path: '/admin/user/searches', name: 'user_searches')]
        #[IsGranted('ROLE_ADMIN')]
        public function usersSearches(PersistenceRegistry $doctrine, Request $request): Response
        {
            $userSearchesFields = new SearchBarFields();

            $userSearchesTypes = $this->createForm(SearchBarTypes::class, $userSearchesFields);

            $userSearches = [];

            $userSearchesTypes->handleRequest($request);

            $formData = $userSearchesTypes->getData();

            if($userSearchesTypes->isSubmitted() && $userSearchesTypes->isValid()) {
                $em = $doctrine->getManager();

                $userSearches = $em
                    ->getRepository(Users::class)
                    ->createQueryBuilder('u')
                    ->where('u.pseudonyme LIKE :pseudonyme')
                    ->setParameter('pseudonyme', '%' . $formData->getKeyword() . '%')
                    ->getQuery()
                    ->getResult()
                ;
            }

            return $this->render('admins/userSearches.html.twig', [
                'searches' => $userSearchesTypes->createView(),
                'usersList' => $userSearches,
                'keyword' => $formData->getKeyword(),
            ]);
        }
    }
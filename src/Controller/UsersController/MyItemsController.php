<?php

    namespace App\Controller\UsersController;

    use App\Entity\Tables\Users\Posts;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;

    class MyItemsController extends AbstractController
    {
        #[Route(path: '/user/post/items', name: 'user_post_items')]
        public function myItems(PersistenceRegistry $doctrine): Response
        {
            $em = $doctrine->getManager();

            $user = $this->getUser()->getUserIdentifier();

            $myItems = $em->getRepository(Posts::class)->findBy(
                ['author' => $user],
                ['publicationDate' => 'DESC'],
            );

            return $this->render('users/myItems.html.twig', [
                'myItems' => $myItems,
            ]);
        }
    }
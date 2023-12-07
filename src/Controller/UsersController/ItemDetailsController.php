<?php

    namespace App\Controller\UsersController;

    use App\Entity\Tables\Users\Posts;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ItemDetailsController extends AbstractController
    {
        #[Route(path: '/user/post/itemDetail/{itemId}', name: 'user_item_details')]
        //#[IsGranted('ROLE_USER')]
        public function itemDetails(PersistenceRegistry $doctrine, $itemId): Response
        {
            $em = $doctrine->getManager();

            $itemDetails = $em->getRepository(Posts::class)->find($itemId);

            if(!$itemDetails) {
                throw $this->createNotFoundException('L\'article que vous cherchez n\'existe plus');
            }

            return $this->render('users/itemDetails.html.twig', [
                'itemDetail' => $itemDetails,
            ]);
        }
    }
<?php

    namespace App\Controller\UsersController;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class DashboardController extends AbstractController
    {
        #[Route(path: '/user/dashboard', name: 'user_dashboard')]
        //#[IsGranted('ROLE_USER')]
        public function userDashboard(): Response
        {
            return $this->render('users/dashboard.html.twig');
        }
    }
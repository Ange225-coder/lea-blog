<?php

    namespace App\Controller\AdminsController;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class DashboardController extends AbstractController
    {
        #[Route(path: '/admin/dashboard', name: 'admin_dashboard')]
        #[IsGranted('ROLE_ADMIN')]
        public function adminDashboard(): Response
        {
            return $this->render('admins/dashboard.html.twig');
        }
    }
<?php

    namespace App\Controller\AdminsController;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class AdminHomePageController extends AbstractController
    {
        #[Route(path: '/admin/home', name: 'admin_home')]
        public function adminHomepage(): Response
        {
            return $this->render('admins/homePage.html.twig');
        }
    }
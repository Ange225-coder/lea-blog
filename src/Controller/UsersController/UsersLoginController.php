<?php

    namespace App\Controller\UsersController;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

    class UsersLoginController extends AbstractController
    {
        #[Route(path: '/user/login', name: 'user_login')]
        public function login(AuthenticationUtils $authenticationUtils): Response
        {
            // if ($this->getUser()) {
            //     return $this->redirectToRoute('target_path');
            // }

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('users/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
            ]);
        }

        #[Route(path: '/user/logout', name: 'user_logout')]
        public function logout(): void
        {
            throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        }
    }

<?php

    namespace App\Security;

    use App\Entity\Tables\Admins\Admins;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
    use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
    use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
    use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
    use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
    use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
    use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
    use Symfony\Component\Security\Http\SecurityRequestAttributes;
    use Symfony\Component\Security\Http\Util\TargetPathTrait;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

    class AdminsAuthenticator extends AbstractLoginFormAuthenticator
    {
        use TargetPathTrait;

        public const LOGIN_ROUTE = 'admin_login';

        private PersistenceRegistry $doctrine;
        private UserPasswordHasherInterface $passwordHasher;

        public function __construct(private UrlGeneratorInterface $urlGenerator, PersistenceRegistry $doctrine, UserPasswordHasherInterface $passwordHasher)
        {
            $this->doctrine = $doctrine;
            $this->passwordHasher = $passwordHasher;
        }

        public function authenticate(Request $request): Passport
        {
            $username = $request->request->get('username', '');
            $plainPass = $request->request->get('password', '');

            $em = $this->doctrine->getManager();
            $admin = $em->getRepository(Admins::class)->findOneBy([
                'username' => $username,
            ]);

            if(!$admin) {
                throw new CustomUserMessageAuthenticationException('Utilisateur invalide');
            }

            if(!$this->passwordHasher->isPasswordValid($admin, $plainPass)) {
                throw new CustomUserMessageAuthenticationException('Mot de passe invalide');
            }

            $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $username);

            return new Passport(
                new UserBadge($username),
                new PasswordCredentials($request->request->get('password', '')),
                [
                    new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),            ]
            );
        }

        public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
        {
            if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
                return new RedirectResponse($targetPath);
            }

            // For example:
            return new RedirectResponse($this->urlGenerator->generate('admin_dashboard'));
            //throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
        }

        protected function getLoginUrl(Request $request): string
        {
            return $this->urlGenerator->generate(self::LOGIN_ROUTE);
        }
    }

<?php

    namespace App\Security;

    use App\Entity\Tables\Users\Users;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
    use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
    use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
    use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
    use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
    use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
    use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
    use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
    use Symfony\Component\Security\Http\SecurityRequestAttributes;
    use Symfony\Component\Security\Http\Util\TargetPathTrait;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

    class UsersAuthenticator extends AbstractLoginFormAuthenticator
    {
        use TargetPathTrait;

        public const LOGIN_ROUTE = 'user_login';

        private PersistenceRegistry $doctrine;
        private UserPasswordHasherInterface $passwordHasher;

        public function __construct(private UrlGeneratorInterface $urlGenerator, PersistenceRegistry $doctrine, UserPasswordHasherInterface $passwordHasher)
        {
            $this->doctrine = $doctrine;
            $this->passwordHasher = $passwordHasher;
        }

        public function authenticate(Request $request): Passport
        {
            $pseudonyme = $request->request->get('pseudonyme', '');
            $plainPass = $request->request->get('password', '');

            $user = $this->doctrine->getRepository(Users::class)->findOneBy([
                'pseudonyme' => $pseudonyme,
            ]);

            if(!$user) {
                throw new CustomUserMessageAuthenticationException('Pseudonyme incorrect');
            }

            if(!$this->passwordHasher->isPasswordValid($user, $plainPass)) {
                throw new CustomUserMessageAuthenticationException('Mot de passe incorrect');
            }

            $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $pseudonyme);

            return new Passport(
                new UserBadge($pseudonyme),
                new PasswordCredentials($request->request->get('password', '')),
                [
                    new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                    new RememberMeBadge(),
                ]
            );
        }

        public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
        {
            if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
                return new RedirectResponse($targetPath);
            }

            // For example:
            return new RedirectResponse($this->urlGenerator->generate('user_dashboard'));
            //throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
        }

        protected function getLoginUrl(Request $request): string
        {
            return $this->urlGenerator->generate(self::LOGIN_ROUTE);
        }
    }

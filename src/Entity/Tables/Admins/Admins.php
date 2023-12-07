<?php

    namespace App\Entity\Tables\Admins;

    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: 'admins')]
    class Admins implements UserInterface, PasswordAuthenticatedUserInterface
    {
        #[ORM\Id]
        #[ORM\GeneratedValue(strategy: 'AUTO')]
        #[ORM\Column(type: 'integer')]
        private int $id;

        #[ORM\Column(type: 'string', length: 55, unique: true)]
        private string $username;

        #[ORM\Column(type: 'string', length: 55, unique: true)]
        private string $email;

        #[ORM\Column(type: 'string')]
        private string $password;

        #[ORM\Column()]
        private array $roles = [];

        //setters
        public function setId(int $id): void
        {
            $this->id = $id;
        }

        public function setUsername(string $username): void
        {
            $this->username = $username;
        }

        public function setEmail(string $email): void
        {
            $this->email = $email;
        }

        public function setPassword(string $password): void
        {
            $this->password = $password;
        }

        public function setRoles(array $roles): void
        {
            $this->roles = $roles;
        }


        //getters
        public function getId(): int
        {
            return $this->id;
        }

        public function getUsername(): string
        {
            return $this->username;
        }

        public function getEmail(): string
        {
            return $this->email;
        }

        public function getPassword(): ?string
        {
            return $this->password;
        }

        public function getRoles(): array
        {
            $roles = $this->roles;

            if(!in_array('ROLE_ADMIN', $roles)) {
                $roles[] = 'ROLE_ADMIN';
            }

            return $roles;
        }

        public function getUserIdentifier(): string
        {
            return $this->username;
        }

        public function eraseCredentials(): void
        {
        }
    }
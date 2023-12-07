<?php

    namespace App\Entity\Tables\Users;

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

    #[ORM\Entity]
    #[ORM\Table(name: 'users')]
    class Users implements UserInterface, PasswordAuthenticatedUserInterface
    {
        #[ORM\Id]
        #[ORM\GeneratedValue(strategy: 'AUTO')]
        #[ORM\Column(type: 'integer')]
        private int $id;

        #[ORM\Column(type: 'string', length: 55, unique: true)]
        private string $pseudonyme;

        #[ORM\Column(type: 'string', length: 55, unique: true)]
        private string $email;

        #[ORM\Column(type: 'string')]
        private string $password;

        #[ORM\Column]
        private array $roles = [];

        //setters
        public function setId(int $id): void
        {
            $this->id = $id;
        }

        public function setPseudonyme(string $pseudonyme): void
        {
            $this->pseudonyme = $pseudonyme;
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

        public function getPseudonyme(): string
        {
            return $this->pseudonyme;
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

            if(!in_array('ROLE_USER', $roles)) {
                $roles[] = 'ROLE_USER';
            }

            return $roles;
        }

        public function getUserIdentifier(): string
        {
            return $this->pseudonyme;
        }

        public function eraseCredentials(): void
        {
        }
    }
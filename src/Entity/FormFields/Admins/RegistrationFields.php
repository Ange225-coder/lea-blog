<?php

    namespace App\Entity\FormFields\Admins;

    use Symfony\Component\Validator\Constraints as Assert;

    class RegistrationFields
    {
        #[Assert\NotBlank(message: 'Remplissez ce champs')]
        #[Assert\Length(
            min: 4,
            max: 12,
            minMessage: 'Nom d\'utilisateur trop court, min: 4',
            maxMessage: 'Nom d\'utilisateur trop long, max: 12'
        )]
        private string $username;

        #[Assert\NotBlank(message: 'Un email est requis pour l\'administration')]
        #[Assert\Regex(
            pattern: "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/",
            message: 'Format email incorrect'
        )]
        private string $email;

        #[Assert\NotBlank(message: 'Entrer un mot de passe')]
        #[Assert\Regex(
            pattern: "/^[a-zA-Z0-9\_\@$. ]{6,16}$/",
            message: 'Format du mot de passe invalide'
        )]
        private string $password;

        //setters
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


        //getters
        public function getUsername(): string
        {
            return $this->username;
        }

        public function getEmail(): string
        {
            return $this->email;
        }

        public function getPassword(): string
        {
            return $this->password;
        }
    }
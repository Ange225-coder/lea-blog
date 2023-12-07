<?php

    namespace App\Entity\FormFields\Users;

    use Symfony\Component\Validator\Constraints as Assert;

    class RegistrationFields
    {
        #[Assert\NotBlank(message: 'Entrer un pseudonyme')]
        //#[Assert\Regex(
            //pattern: "/^(?=.*[a-z])(?=.*[A-Z]?)(?=.*[0-9]?)(?=.*\.?])[a-zA-Z0-9.]{4,16}$/",
            //message: 'Pseudonyme invalide'
        //)]
        #[Assert\Length(
            min: 4,
            max: 12,
            minMessage: 'Ce pseudonyme est trop court, min: 4',
            maxMessage: 'Ce pseudonyme est trop long, max: 12'
        )]
        private string $pseudonyme;

        #[Assert\Email(message: 'Entrer un email correct')]
        #[Assert\Regex(
            pattern: "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/",
            message: 'Email incorrect'
        )]
        #[Assert\NotBlank(message: 'Entrer un email')]
        private string $email;

        #[Assert\NotBlank(message: 'Entrer un mot de passe')]
        #[Assert\Regex(
            pattern: "/^[a-zA-Z0-9\_\@$. ]{6,16}$/",
            message: 'Format du mot de passe invalide'
        )]
        private string $password;

        //setters
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


        //getters
        public function getPseudonyme(): string
        {
            return $this->pseudonyme;
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
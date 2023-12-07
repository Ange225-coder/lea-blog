<?php

    namespace App\Entity\FormFields\Users;

    use Symfony\Component\Validator\Constraints as Assert;

    class ForgottenPasswordEmailCheckingFields
    {
        #[Assert\NotBlank(message: 'Ce champs ne peux pas rester vide')]
        #[Assert\Email(message: 'Entrer un email au format correct')]
        private string $email;

        public function setEmail(string $email): void
        {
            $this->email = $email;
        }

        public function getEmail(): string
        {
            return $this->email;
        }
    }
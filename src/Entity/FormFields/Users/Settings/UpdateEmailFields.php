<?php

    namespace App\Entity\FormFields\Users\Settings;

    use Symfony\Component\Validator\Constraints as Assert;

    class UpdateEmailFields
    {
        private string $currentEmail;

        #[Assert\NotBlank(message: 'Ce champs ne peut pas rester vide')]
        #[Assert\Regex(
            pattern: "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/",
            message: 'Format incorrect'
        )]
        #[Assert\Email(message: 'Entrer un email au format correct')]
        private string $newEmail;

        //setters
        public function setCurrentEmail(string $currentEmail): void
        {
            $this->currentEmail = $currentEmail;
        }

        public function setNewEmail(string $newEmail): void
        {
            $this->newEmail = $newEmail;
        }


        //getters
        public function getCurrentEmail(): string
        {
            return $this->currentEmail;
        }

        public function getNewEmail(): string
        {
            return $this->newEmail;
        }
    }
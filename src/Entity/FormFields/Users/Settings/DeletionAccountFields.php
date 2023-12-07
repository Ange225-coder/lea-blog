<?php

    namespace App\Entity\FormFields\Users\Settings;

    use Symfony\Component\Validator\Constraints as Assert;

    class DeletionAccountFields
    {
        #[Assert\NotBlank(message: 'Entrer un mot de passe pour continuer la suppression')]
        private string $password;


        public function setPassword(string $password): void
        {
            $this->password = $password;
        }

        public function getPassword(): string
        {
            return $this->password;
        }
    }
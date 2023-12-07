<?php

    namespace App\Entity\FormFields\Admins;

    use Symfony\Component\Validator\Constraints as Assert;

    class DeletionsFields
    {
        #[Assert\NotBlank(message: 'Entrer un mot de passe pour effectuer une suppression')]
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
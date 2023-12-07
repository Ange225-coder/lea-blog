<?php

    namespace App\Entity\FormFields\Users;

    use Symfony\Component\Validator\Constraints as Assert;

    class DeletePostFields
    {
        #[Assert\NotBlank(message: 'Entrer un mot de passe')]
        private string $password;

        //setter
        public function setPassword(string $password): void
        {
            $this->password = $password;
        }


        //getter
        public function getPassword(): string
        {
            return $this->password;
        }
    }
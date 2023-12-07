<?php

    namespace App\Entity\FormFields\Users\Settings;

    use Symfony\Component\Validator\Constraints as Assert;

    class UpdatePasswordFields
    {
        #[Assert\NotBlank(message: 'Entrer un mot de passe')]
        private string $currentPassword;

        #[Assert\NotBlank(message: 'Entrer un nouveau mot de passe')]
        #[Assert\Regex(
            pattern: "/^[a-zA-Z0-9\_\@$. ]{6,16}$/",
            message: 'Format du mot de passe invalide'
        )]
        private string $newPassword;

        #[Assert\NotBlank(message: 'Confirmez votre mot de passe')]
        private string $repeatNewPassword;


        //setters
        public function setCurrentPassword(string $currentPassword): void
        {
            $this->currentPassword = $currentPassword;
        }

        public function setNewPassword(string $newPassword): void
        {
            $this->newPassword = $newPassword;
        }

        public function setRepeatNewPassword(string $repeatNewPassword): void
        {
            $this->repeatNewPassword = $repeatNewPassword;
        }


        //getters
        public function getCurrentPassword(): string
        {
            return $this->currentPassword;
        }

        public function getNewPassword(): string
        {
            return $this->newPassword;
        }

        public function getRepeatNewPassword(): string
        {
            return $this->repeatNewPassword;
        }
    }
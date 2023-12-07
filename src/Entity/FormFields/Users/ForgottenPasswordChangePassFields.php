<?php

    namespace App\Entity\FormFields\Users;

    use Symfony\Component\Validator\Constraints as Assert;

    class ForgottenPasswordChangePassFields
    {
        #[Assert\NotBlank(message: 'Ce champs ne peux pas rester vide')]
        #[Assert\Regex(
            pattern: "/^[a-zA-Z0-9\_\@$. ]{6,16}$/",
            message: 'Format du mot de passe invalide'
        )]
        private string $newPassword;

        #[Assert\NotBlank(message: 'Confirmer le nouveau mot de passe')]
        private string $repeatNewPassword;

        //setters
        public function setNewPassword(string $newPassword): void
        {
            $this->newPassword = $newPassword;
        }

        public function setRepeatNewPassword(string $repeatNewPassword): void
        {
            $this->repeatNewPassword = $repeatNewPassword;
        }


        //getters
        public function getNewPassword(): string
        {
            return $this->newPassword;
        }

        public function getRepeatNewPassword(): string
        {
            return $this->repeatNewPassword;
        }
    }
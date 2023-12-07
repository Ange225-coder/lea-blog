<?php

    namespace App\Entity\FormFields\Users;

    use Symfony\Component\Validator\Constraints as Assert;

    class CommentsFields
    {
        #[Assert\NotBlank(message: 'Écrivez un commentaire avant de soumettre le formulaire')]
        private string $comment;


        public function setComment(string $comment): void
        {
            $this->comment = $comment;
        }

        public function getComment(): string
        {
            return $this->comment;
        }
    }
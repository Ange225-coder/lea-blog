<?php

    namespace App\Entity\FormFields\Users;

    use Symfony\Component\Validator\Constraints as Assert;

    class SearchBarFields
    {
        #[Assert\NotBlank()]
        private string $keyword = '';


        public function setKeyword(string $keyword): void
        {
            $this->keyword = $keyword;
        }


        public function getKeyword(): string
        {
            return $this->keyword;
        }
    }
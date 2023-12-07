<?php

    namespace App\Entity\FormFields\Users;

    use Symfony\Component\Validator\Constraints as Assert;

    class ModifyPostFields
    {
        #[Assert\NotBlank(message: 'Entrer un title pour effectuer la modification')]
        #[Assert\Length(
            min: 3,
            max: 30,
            minMessage: 'Ce titre est trop court min: 3',
            maxMessage: 'Ce titre est trop long max: 30'
        )]
        private string $currentTitle;

        #[Assert\NotBlank(message: 'Entrer du contenu pour effectuer la modification')]
        private string $currentContent;

        //setters
        public function setCurrentTitle(string $currentTitle): void
        {
            $this->currentTitle = $currentTitle;
        }

        public function setCurrentContent(string $currentContent): void
        {
            $this->currentContent = $currentContent;
        }


        //getters
        public function getCurrentTitle(): string
        {
            return $this->currentTitle;
        }

        public function getCurrentContent(): string
        {
            return $this->currentContent;
        }
    }
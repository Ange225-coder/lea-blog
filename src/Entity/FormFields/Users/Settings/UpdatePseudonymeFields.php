<?php

    namespace App\Entity\FormFields\Users\Settings;

    use Symfony\Component\Validator\Constraints as Assert;

    class UpdatePseudonymeFields
    {
        private string $currentPseudonyme;

        #[Assert\NotBlank(message: 'Ce champs ne peut pas rester vide')]
        #[Assert\Length(
            min: 4,
            max: 12,
            minMessage: 'Ce pseudonyme est trop court, min: 4',
            maxMessage: 'Ce pseudonyme est trop long, max: 12'
        )]
        private string $newPseudonyme;

        //setters
        public function setCurrentPseudonyme(string $currentPseudonyme): void
        {
            $this->currentPseudonyme = $currentPseudonyme;
        }

        public function setNewPseudonyme(string $newPseudonyme): void
        {
            $this->newPseudonyme = $newPseudonyme;
        }

        //getters
        public function getCurrentPseudonyme(): string
        {
            return $this->currentPseudonyme;
        }

        public function getNewPseudonyme(): string
        {
            return $this->newPseudonyme;
        }
    }
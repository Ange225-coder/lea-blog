<?php

    namespace App\Entity\FormFields\Users;

    use Symfony\Component\Validator\Constraints as Assert;


    class PostsFields
    {
        //#[Assert\NotBlank(message: 'Un auteur est requis')]
        //private  $author;

        #[Assert\Length(
            min: 3,
            max: 30,
            minMessage: 'Ce titre est trop court min: 3',
            maxMessage: 'Ce titre est trop long max: 30'
        )]
        #[Assert\NotBlank(message: 'Entrer le titre du post')]
        private string $title;

        #[Assert\NotBlank(message: 'Aucun contenu pour ce post')]
        private string $content;


        //setters
        //public function setAuthor( $author): void
        //{
         //   $this->author = $author;
        //}

        public function setTitle(string $title): void
        {
            $this->title = $title;
        }

        public function setContent(string $content): void
        {
            $this->content = $content;
        }


        //getters
        //public function getAuthor(): string
        //{
       //     return $this->author;
        //}

        public function getTitle(): string
        {
            return $this->title;
        }

        public function getContent(): string
        {
            return $this->content;
        }
    }
<?php

    namespace App\Entity\Tables\Users;

    use Doctrine\ORM\Mapping as ORM;
    use DateTime;

    #[ORM\Entity]
    #[ORM\Table(name: 'posts')]
    class Posts
    {
        #[ORM\Id]
        #[ORM\GeneratedValue(strategy: 'AUTO')]
        #[ORM\Column(type: 'integer')]
        private int $id;

        #[ORM\Column(type: 'string', length: 55)]
        private string $author;

        #[ORM\Column(type: 'string', length: 128)]
        private string $title;

        #[ORM\Column(type: 'text')]
        private string $content;

        #[ORM\Column(type: 'datetime')]
        private DateTime $publicationDate;


        //setters
        public function setId(int $id): void
        {
            $this->id = $id;
        }

        public function setAuthor(string $author): void
        {
            $this->author = $author;
        }

        public function setTitle(string $title): void
        {
            $this->title = $title;
        }

        public function setContent(string $content): void
        {
            $this->content = $content;
        }


        public function setPublicationDate($publicationDate): void
        {
            $this->publicationDate = $publicationDate;
        }


        //getters
        public function getId(): int
        {
            return $this->id;
        }

        public function getAuthor(): string
        {
            return $this->author;
        }

        public function getTitle(): string
        {
            return $this->title;
        }

        public function getContent(): string
        {
            return $this->content;
        }

        public function getPublicationDate(): DateTime
        {
            return $this->publicationDate;
        }
    }
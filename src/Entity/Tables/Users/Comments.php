<?php

    namespace App\Entity\Tables\Users;

    use Doctrine\ORM\Mapping as ORM;
    use DateTime;

    #[ORM\Entity]
    #[ORM\Table(name: 'comments')]
    class Comments
    {
        #[ORM\Id]
        #[ORM\GeneratedValue(strategy: 'AUTO')]
        #[ORM\Column(type: 'integer')]
        private int $id;

        #[ORM\Column(type: 'string', length: 55)]
        private string $author;

        #[ORM\Column(type: 'string', length: 128)]
        private string $post;

        #[ORM\Column(type: 'text')]
        private string $comment;

        #[ORM\Column(type: 'datetime')]
        private DateTime $commentDate;


        //setters
        public function setId(int $id): void
        {
            $this->id = $id;
        }

        public function setAuthor(string $author): void
        {
            $this->author = $author;
        }

        public function setPost(string $post): void
        {
            $this->post = $post;
        }

        public function setComment(string $comment): void
        {
            $this->comment = $comment;
        }

        public function setCommentDate(DateTime $commentDate): void
        {
            $this->commentDate = $commentDate;
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

        public function getPost(): string
        {
            return $this->post;
        }

        public function getComment(): string
        {
            return $this->comment;
        }

        public function getCommentDate(): DateTime
        {
            return $this->commentDate;
        }
    }
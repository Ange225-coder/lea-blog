<?php

    namespace App\Controller\UsersController;

    use App\Entity\Tables\Users\Comments;
    use App\Entity\Tables\Users\Posts;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use App\Entity\FormFields\Users\CommentsFields;
    use App\FormsTypes\Users\CommentsTypes;

    class CommentsPostController extends AbstractController
    {
        #[Route(path: '/user/post/comments/{postId}', name: 'user_post_comments')]
        #[IsGranted('ROLE_USER')]
        public function commentPost($postId, PersistenceRegistry $doctrine, Request $request): Response
        {
            //get post to comment here
            $em = $doctrine->getManager();
            $post = $em->getRepository(Posts::class)->find($postId);


            $commentFields = new CommentsFields();
            $commentEntity = new Comments();

            $commentTypes = $this->createForm(CommentsTypes::class, $commentFields);

            $commentTypes->handleRequest($request);

            if($commentTypes->isSubmitted() && $commentTypes->isValid()) {
                $formData = $commentTypes->getData();

                $commentEntity->setAuthor($this->getUser()->getUserIdentifier());
                $commentEntity->setPost($post->getTitle());
                $commentEntity->setComment($formData->getComment());
                $commentEntity->setCommentDate(new \DateTime());

                $em->persist($commentEntity);
                $em->flush();

                return $this->redirectToRoute('user_post_comments', ['postId' => $postId]);
            }

            //get comments associated with the post
            $comments = $em->getRepository(Comments::class)->findBy(
                ['post' => $post->getTitle() ],
                ['commentDate' => 'DESC'],
            );

            return $this->render('users/commentPost.html.twig', [
                'commentForm' => $commentTypes->createView(),
                'post' => $post,
                'comments' => $comments,
            ]);
        }
    }
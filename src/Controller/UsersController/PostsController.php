<?php

    namespace App\Controller\UsersController;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use App\Entity\FormFields\Users\PostsFields;
    use App\FormsTypes\Users\PostsTypes;
    use App\Entity\Tables\Users\Posts;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use Doctrine\ORM\EntityManagerInterface;

    class PostsController extends AbstractController
    {
        #[Route(path: '/user/post/create', name: 'user_create_post')]
        //#[IsGranted('ROLE_USER')]
        public function createPost(Request $request, EntityManagerInterface $entityManager): Response
        {
            $postEntity = new Posts();

            $postFields = new PostsFields();

            $postTypes = $this->createForm(PostsTypes::class, $postFields);

            $postTypes->handleRequest($request);

            if($postTypes->isSubmitted() && $postTypes->isValid()) {
                $formData = $postTypes->getData();

                //$em = $doctrine->getManager();

                $postEntity->setAuthor($this->getUser()->getUserIdentifier());
                $postEntity->setTitle($formData->getTitle());
                $postEntity->setContent($formData->getContent());
                $postEntity->setPublicationDate(new \DateTime());

                $entityManager->persist($postEntity);
                $entityManager->flush();

                return $this->redirectToRoute('home_page');
            }

            return $this->render('users/createPost.html.twig', [
                'post' => $postTypes->createView(),
            ]);
        }
    }
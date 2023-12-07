<?php

    namespace App\Controller\UsersController;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use App\Entity\Tables\Users\Posts;
    use App\Entity\FormFields\Users\ModifyPostFields;
    use App\FormsTypes\Users\ModifyPostTypes;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ModifyItemController extends AbstractController
    {
        #[Route(path: '/user/post/modify/{postId}', name: 'user_post_modify')]
        #[IsGranted('ROLE_USER')]
        public function modifyItem(PersistenceRegistry $doctrine, $postId, Request $request): Response
        {
            $modifyPostFields = new ModifyPostFields();

            $em = $doctrine->getManager();
            $post = $em->getRepository(Posts::class)->find($postId);

            //pre-fill textarea with current content
            $contentFromDb = $post->getContent();
            $modifyPostFields->setCurrentContent($contentFromDb);

            $modifyPostTypes = $this->createForm(ModifyPostTypes::class, $modifyPostFields);

            $modifyPostTypes->handleRequest($request);

            if($modifyPostTypes->isSubmitted() && $modifyPostTypes->isValid()) {

                $post->setTitle($modifyPostFields->getCurrentTitle());
                $post->setContent($modifyPostFields->getCurrentContent());

                $em->flush();

                return $this->redirectToRoute('user_post_items');
            }

            return $this->render('users/modifyItem.html.twig', [
                'modifyPost' => $modifyPostTypes->createView(),
                'post' => $post,
            ]);
        }
    }
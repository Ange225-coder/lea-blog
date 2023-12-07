<?php

    namespace App\Controller\AdminsController;

    use App\Entity\FormFields\Admins\DeletionsFields;
    use App\Entity\Tables\Admins\Admins;
    use App\Entity\Tables\Users\Comments;
    use App\FormsTypes\Admins\DeletionsTypes;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\Response;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

    class CommentDetailsController extends AbstractController
    {
        #[Route(path: '/admin/comment/details/{commentId}', name: 'comment_details')]
        #[IsGranted('ROLE_ADMIN')]
        public function commentDetails($commentId, PersistenceRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): Response
        {
            $em = $doctrine->getManager();
            $comment = $em->getRepository(Comments::class)->find($commentId);

            $deletionFields = new DeletionsFields();

            $deletionTypes = $this->createForm(DeletionsTypes::class, $deletionFields);

            $deletionTypes->handleRequest($request);

            if($deletionTypes->isSubmitted() && $deletionTypes->isValid()) {
                $formData = $deletionTypes->getData();

                $adminLogged = $em->getRepository(Admins::class)->findOneBy([
                    'username' => $this->getUser()->getUserIdentifier(),
                ]);

                if(!$passwordHasher->isPasswordValid($adminLogged, $formData->getPassword())) {
                    $deletionTypes->get('password')->addError(new FormError('Impossible de supprimer le commentaire'));
                }
                else {
                    $em->remove($comment);
                    $em->flush();

                    return $this->redirectToRoute('admin_dashboard');
                }
            }

            return $this->render('admins/commentDetails.html.twig', [
                'comment' => $comment,
                'deletion' => $deletionTypes->createView(),
            ]);
        }
    }
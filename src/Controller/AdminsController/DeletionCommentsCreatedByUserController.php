<?php

    /**
     * in this controller I used the same
     * App\Entity\FormFields\Admins\DeletionsFields; and
     * App\FormsTypes\Admins\DeletionsTypes;
     * that I already use for the controller to a posts deletions
     */

    namespace App\Controller\AdminsController;

    use App\Entity\Tables\Admins\Admins;
    use App\Entity\Tables\Users\Comments;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use App\Entity\FormFields\Admins\DeletionsFields;
    use App\FormsTypes\Admins\DeletionsTypes;
    use Doctrine\Persistence\ManagerRegistry as PersistenceRegistry;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class DeletionCommentsCreatedByUserController extends AbstractController
    {
        #[Route(path: '/admin/userDetails/commentDeletion/{comId}', name: 'user_details_com_deletion')]
        #[IsGranted('ROLE_ADMIN')]
        public function deletionCommentsCreatedByUser($comId, PersistenceRegistry $doctrine, UserPasswordHasherInterface $passwordHasher, Request $request): Response
        {
            $em = $doctrine->getManager();
            $comment = $em->getRepository(Comments::class)->find($comId);

            $deletionComFields = new DeletionsFields();

            $deletionComTypes = $this->createForm(DeletionsTypes::class, $deletionComFields);

            $deletionComTypes->handleRequest($request);

            if($deletionComTypes->isSubmitted() && $deletionComTypes->isValid()) {
                $formData = $deletionComTypes->getData();

                $adminLogged = $em->getRepository(Admins::class)->findOneBy([
                    'username' => $this->getUser()->getUserIdentifier(),
                ]);

                if($passwordHasher->isPasswordValid($adminLogged, $formData->getPassword())) {
                    $em->remove($comment);
                    $em->flush();

                    return $this->redirectToRoute('admin_user_list');
                }
                else {
                    $deletionComTypes->get('password')->addError(new FormError('Suppression impossible mot de passe incorrect'));
                }
            }

            return $this->render('admins/deletionComment.html.twig', [
                'deletionCom' => $deletionComTypes->createView(),
                'com' => $comment,
            ]);
        }
    }
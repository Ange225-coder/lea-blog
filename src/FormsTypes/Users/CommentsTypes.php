<?php

    namespace App\FormsTypes\Users;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use App\Entity\FormFields\Users\CommentsFields;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class CommentsTypes extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('comment', TextareaType::class, [
                    'attr' => ['placeholder' => 'Écrivez votre commentaire ici']
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => CommentsFields::class,
            ]);
        }
    }
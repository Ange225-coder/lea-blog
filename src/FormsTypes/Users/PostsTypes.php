<?php

    namespace App\FormsTypes\Users;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Entity\FormFields\Users\PostsFields;

    class PostsTypes extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                //->add('author', TextType::class, [
                  //  'attr' => ['readonly' => true],
                    //'label' => 'Auteur',
                //])

                ->add('title', TextType::class, [
                    'attr' => ['placeholder' => 'Entrer un titre'],
                    'label' => 'Titre',
                ])

                ->add('content', TextareaType::class, [
                    'attr' => ['placeholder' => 'Entrer le contenu de votre post ici'],
                    'label' => 'Contenu'
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => PostsFields::class,
            ]);
        }
    }
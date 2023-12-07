<?php

    namespace App\FormsTypes\Users;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Entity\FormFields\Users\ModifyPostFields;

    class ModifyPostTypes extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('currentTitle', TextType::class, [
                    'label' => 'Titre actuel',
                ])

                ->add('currentContent', TextareaType::class, [
                    'label' => 'Contenu actuel'
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => ModifyPostFields::class,
            ]);
        }
    }
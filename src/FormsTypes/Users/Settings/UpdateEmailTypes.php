<?php

    namespace App\FormsTypes\Users\Settings;

    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\AbstractType;
    use App\Entity\FormFields\Users\Settings\UpdateEmailFields;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class UpdateEmailTypes extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('currentEmail', EmailType::class, [
                    'attr' => ['readonly' => true]
                ])

                ->add('newEmail', EmailType::class, [
                    'attr' => ['placeholder' => 'Entrer un nouvel email']
                ])

            ;
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => UpdateEmailFields::class,
            ]);
        }
    }
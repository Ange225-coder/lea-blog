<?php

    namespace App\FormsTypes\Users\Settings;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use App\Entity\FormFields\Users\Settings\UpdatePasswordFields;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class UpdatePasswordTypes extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('currentPassword', PasswordType::class, [
                    'attr' => ['placeholder' => 'Mot de passe actuel'],
                    'label' => 'Mot de pass actuel'
                ])

                ->add('newPassword', PasswordType::class, [
                    'attr' => ['placeholder' => 'Entrer un nouveau mot de passe'],
                    'label' => 'Nouveau mot de passe'
                ])

                ->add('repeatNewPassword', PasswordType::class, [
                    'attr' => ['placeholder' => 'Confirmer le mot de passe'],
                    'label' => 'Confirmer le mot de passe'
                ])
            ;
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => UpdatePasswordFields::class,
            ]);
        }
    }
<?php

    namespace App\FormsTypes\Users;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use App\Entity\FormFields\Users\ForgottenPasswordChangePassFields;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class ForgottenPasswordChangePassTypes extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('newPassword', PasswordType::class, [
                    'attr' => ['placeholder' => 'Nouveau mot de passe']
                ])

                ->add('repeatNewPassword', PasswordType::class, [
                    'attr' => ['placeholder' => 'Confirmez le mot de passe']
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => ForgottenPasswordChangePassFields::class,
            ]);
        }
    }
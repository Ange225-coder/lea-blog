<?php

    namespace App\FormsTypes\Users;

    use Symfony\Component\Form\AbstractType;
    use App\Entity\FormFields\Users\RegistrationFields;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class RegistrationType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('pseudonyme', TextType::class, [
                    'attr' => ['placeholder' => 'Votre pseudonyme']
                ])

                ->add('email', EmailType::class, [
                    'attr' => ['placeholder' => 'Votre email']
                ])

                ->add('password', PasswordType::class, [
                    'attr' => ['placeholder' => 'Mot de passe']
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => RegistrationFields::class,
            ]);
        }
    }
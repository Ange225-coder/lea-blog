<?php

    namespace App\FormsTypes\Users;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Entity\FormFields\Users\DeletePostFields;

    class DeletePostTypes extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('password', PasswordType::class, [
                    'attr' => ['placeholder' => 'Entrer votre mot de passe']
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => DeletePostFields::class,
            ]);
        }
    }
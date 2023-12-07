<?php

    namespace App\FormsTypes\Admins;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use App\Entity\FormFields\Admins\DeletionsFields;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class DeletionsTypes extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('password', PasswordType::class, [
                    'attr' => ['placeholder' => 'Mot de passe administrateur']
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => DeletionsFields::class,
            ]);
        }
    }
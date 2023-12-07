<?php

    namespace App\FormsTypes\Users\Settings;

    use Symfony\Component\Form\AbstractType;
    use App\Entity\FormFields\Users\Settings\UpdatePseudonymeFields;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class UpdatePseudonymeTypes extends AbstractType

    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('currentPseudonyme', TextType::class, [
                    'attr' => ['readonly' => true],
                    'label' => 'Pseudonyme actuel'
                ])

                ->add('newPseudonyme', TextType::class, [
                    'attr' => ['placeholder' => 'Entrer un nouveau pseudonyme'],
                    'label' => 'Nouveau pseudo'
                ])
            ;
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => UpdatePseudonymeFields::class,
            ]);
        }
    }
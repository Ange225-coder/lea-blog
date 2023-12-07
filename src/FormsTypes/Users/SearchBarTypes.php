<?php

    namespace App\FormsTypes\Users;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\SearchType;
    use App\Entity\FormFields\Users\SearchBarFields;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class SearchBarTypes extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('keyword',  SearchType::class, [
                    'attr' => [
                        'placeholder' => 'Effectuer une recherche',
                        'autofocus' => true,
                    ]
                ])
            ;
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => SearchBarFields::class,
            ]);
        }
    }
<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Prise de masse'
                ],
                'constraints' => [
                    new Length(['min' => 3, 'max' => 30])
                ],
                'label' => 'Nom de la catégorie'
            ])
            ->add('hexaColor', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length(['min' => 4, 'max' => 7])
                ],
                'label' => 'Code hexadécimal de la couleur',
                'attr' => [
                    'placeholder' => '#FFFFFF'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}

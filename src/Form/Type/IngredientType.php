<?php
// src/Form/Type/IngredientType.php
namespace App\Form\Type;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,
                    [
                        'label' => 'Nom',
                        'required' => true
                    ]
                    )
                ->add('measuringUnit', ChoiceType::class, [
                    'choices'  => [
                        'g' => 'g',
                        'kg' => 'kg',
                        'L' => 'L',
                        'cL' => 'cL',
                        'mL' => 'mL',
                        'pièce(s)' => 'piece',
                    ],
                    'label'=> 'Unité',
                    'attr' => ['class' => 'measuring-unit']
                ])
                ->add('quantity',IntegerType::class,['label' => 'Quantité'])
                ->add('protein',IntegerType::class,['label' => 'Protéine','help' => 'Pour 100g'])
                ->add('carbohydrate',IntegerType::class,['label' => 'Glucides','help' => 'Pour 100g'])
                ->add('fat',IntegerType::class,['label' => 'Lipides','help' => 'Pour 100g']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
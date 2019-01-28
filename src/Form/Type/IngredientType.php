<?php
// src/Form/Type/IngredientType.php
namespace App\Form\Type;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,['label' => 'Nom']);
        $builder->add('quantity',IntegerType::class,['label' => 'Quantité']);
        $builder->add('protein',IntegerType::class,['label' => 'Protéine']);
        $builder->add('carbohydrate',IntegerType::class,['label' => 'Glucides']);
        $builder->add('fat',IntegerType::class,['label' => 'Lipides']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
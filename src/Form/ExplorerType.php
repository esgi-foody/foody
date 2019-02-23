<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ExplorerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', TextType::class, [
                'label' => 'Recherche',
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégories',
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
            ])
            ->add('calorie_min', IntegerType::class, [
                'label' => 'Nombre de calories minimum',
                'required' => false,
            ])
            ->add('calorie_max', IntegerType::class, [
                'label' => 'Nombre de calories maximum',
                'required' => false,
            ])
            ->add('protein_min', IntegerType::class, [
                'label' => 'Nombre de protéines minimum',
                'required' => false,
            ])
            ->add('protein_max', IntegerType::class, [
                'label' => 'Nombre de protéines maximum',
                'required' => false,
            ])
            ->add('carbohydrate_min', IntegerType::class, [
                'label' => 'Nombre de glucides minimum',
                'required' => false,
            ])
            ->add('carbohydrate_max', IntegerType::class, [
                'label' => 'Nombre de glucides maximum',
                'required' => false,
            ])
            ->add('fat_min', IntegerType::class, [
                'label' => 'Nombre de lipides minimum',
                'required' => false,
            ])
            ->add('fat_max', IntegerType::class, [
                'label' => 'Nombre de lipides maximum',
                'required' => false,
            ]);
    }
}

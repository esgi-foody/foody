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
                'attr' => [ 'class' => 'input-field col s6']
            ])
            ->add('calorie_min', IntegerType::class, [
                'label' => 'Calories minimum',
                'required' => false,
               'attr' => [ 'class' => 'input-field col s6']
            ])
            ->add('calorie_max', IntegerType::class, [
                'label' => 'Calories maximum',
                'required' => false,
                'attr' => [ 'class' => 'input-field col s6']
            ])
            ->add('protein_min', IntegerType::class, [
                'label' => 'Protéines minimum',
                'required' => false,
                'attr' => [ 'class' => 'input-field col s6']
            ])
            ->add('protein_max', IntegerType::class, [
                'label' => 'Protéines maximum',
                'required' => false,
                'attr' => [ 'class' => 'input-field col s6']
            ])
            ->add('carbohydrate_min', IntegerType::class, [
                'label' => 'Glucides minimum',
                'required' => false,
                'attr' => [ 'class' => 'input-field col s6']
            ])
            ->add('carbohydrate_max', IntegerType::class, [
                'label' => 'Glucides maximum',
                'required' => false,
                'attr' => [ 'class' => 'input-field col s6']
            ])
            ->add('fat_min', IntegerType::class, [
                'label' => 'Lipides minimum',
                'required' => false,
                'attr' => [ 'class' => 'input-field col s6']
            ])
            ->add('fat_max', IntegerType::class, [
                'label' => 'Lipides maximum',
                'required' => false,
                'attr' => [ 'class' => 'input-field col s6']
            ]);
    }
}

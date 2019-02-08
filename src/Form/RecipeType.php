<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Form\Type\RecipeStepType;
use App\Form\Type\IngredientType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType as TimeTypeField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,['label' => 'Titre'])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_label' => false,
                'required' => true,
                'label' => 'Image'
            ])
            ->add('time',TimeTypeField::class,['label' => 'Temps'])
            ->add('categories', EntityType::class, [
                'label'        => 'Categories',
                'class'        => Category::class,
                'choice_label' => 'name',
                'multiple'     => true,
                'required'     => false,
            ])
            ->add('ingredients', CollectionType::class, [
                'entry_type' => IngredientType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'Ingredients',
            ])
            ->add('recipeSteps', CollectionType::class, [
                'entry_type' => RecipeStepType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'Etapes',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}

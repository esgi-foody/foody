<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\RecipeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ExplorerType extends AbstractType
{
    private $recipeRepository;

    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $maxCalories = $this->recipeRepository->findHighestCalorie()[1];
        $maxChoices = [];
        for ($i = 0; $i < $maxCalories; $i += 100) {
            $maxChoices[$i] = $i;
        }
        $maxChoices[$maxCalories] = $maxCalories;

        $builder
            ->add('query', TextType::class, [
                'label' => 'Un utilisateur, une recette à rechercher ?',
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégories',
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
            ])
            ->add('calorie_min', ChoiceType::class, [
                'label' => 'Calorie minimum',
                'required' => false,
                'choices' => $maxChoices,
            ])
            ->add('calorie_max', ChoiceType::class, [
                'label' => 'Calorie maximum',
                'required' => false,
                'choices' => $maxChoices,
            ]);
    }
}

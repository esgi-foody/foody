<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Recipe;
use App\Entity\RecipeStep;
use App\Entity\Ingredient;
use App\Entity\User;
use Faker;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Placeholder($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Food($faker));

        for ($i = 0; $i < 20; $i++) {
            $recipe = new Recipe();
            $recipe->setTitle($faker->foodName());

            //INGREDIENTS
            $x = rand(3,10);
            for ($y = 0; $y < $x; $y++){
                $arrIngr=[];
                $ingredient = new Ingredient();
                $ingredient->setName($faker->ingredient);
                $ingredient->setQuantity(rand(1,50));
                $ingredient->setProtein(rand(1,10));
                $ingredient->setCarbohydrate(rand(1,10));
                $ingredient->setFat(rand(1,10));
                $ingredient->setRecipe($recipe);
                array_push($arrIngr,$ingredient);

                $manager->persist($ingredient);
            }
            $recipe->getIngredients($arrIngr);

            //RECIPE_STEPS
            $x = rand(3,10);
            for ($y = 0; $y < $x; $y++){
                $arrStep=[];
                $recipeStep = new RecipeStep();
                $recipeStep->setTitle($faker->sentence($nbWords = 5, $variableNbWords = true));
                $recipeStep->setStepNumber($y);
                $recipeStep->setContent($faker->paragraph($nbSentences = 4, $variableNbSentences = true));
                $recipeStep->setRecipe($recipe);
                //$recipeStep->addImage($faker->placeholder());
                array_push($arrStep,$recipeStep);

                $manager->persist($recipeStep);
            }

            $recipe->getRecipeSteps($arrStep);


            $date = date_create_from_format('H:i:s',$faker->time());
            $recipe->setTime($date);
            $recipe->getComments([]);
            $recipe->setPathCoverImg($faker->imageUrl($width = 640, $height = 480));
            $recipe->getRecipeFavorite([]);

            //RANDOM USER SELECTED
            $user = $manager->getRepository(User::class)->find(rand(1,20));
            $recipe->setUserRecipe($user);


            $manager->persist($recipe);
            $manager->flush();
        }
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        // TODO: Implement getDependencies() method.
        return [UserFixtures::class];
    }
}

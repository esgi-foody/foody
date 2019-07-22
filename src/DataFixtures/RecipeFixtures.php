<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Recipe;
use App\Entity\RecipeStep;
use App\Entity\Ingredient;
use App\Entity\User;
use Faker;
use Symfony\Component\HttpFoundation\File\File;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Placeholder($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Food($faker));

        //RECIPE
        for ($i = 0; $i < 100; $i++) {
            $recipe = new Recipe();
            $recipe->setTitle($faker->foodName());
            $protein = rand(1, 15);
            $carbohydrate = rand(1, 15);
            $fat = rand(1, 15);
            $nbIngredients = rand(3, 6);

            //INGREDIENTS
            for ($y = 0; $y < $nbIngredients; $y++){
                $measuringUnit =  ['g','kg','piece','mL','cL','L'];
                $keyMeasuringUnit = array_rand($measuringUnit);
                $ingredient = new Ingredient();
                $ingredient->setName($faker->ingredient);
                $ingredient->setQuantity(rand(1,50));
                $ingredient->setProtein($protein);
                $ingredient->setCarbohydrate($carbohydrate);
                $ingredient->setFat($fat);
                $ingredient->setMeasuringUnit($measuringUnit[$keyMeasuringUnit]);
                $ingredient->setRecipe($recipe);
                $recipe->addIngredient($ingredient);


                $manager->persist($ingredient);
            }

            //RECIPE_STEPS
            for ($y = 0; $y < rand(3,10); $y++){
                $arrStep=[];
                $recipeStep = new RecipeStep();
                $recipeStep->setTitle($faker->sentence( 5,  true));
                $recipeStep->setStepNumber($y);
                $recipeStep->setContent($faker->paragraph( 4,  true));
                $recipeStep->setRecipe($recipe);
                $arrStep[] = $recipeStep ;

                $manager->persist($recipeStep);
            }

            $date = date_create_from_format('H:i:s',$faker->time());

            $recipe->setTime($date);
            $category = $manager->getRepository(Category::class)->find(rand(1,20));
            $recipe->addCategory($category);
            $recipe->setCalory(($nbIngredients * $protein * 4) + ($nbIngredients * $carbohydrate * 4) + ($nbIngredients * $fat * 9));
            $recipe->setProtein($nbIngredients * $protein);
            $recipe->setCarbohydrate($nbIngredients * $carbohydrate);
            $recipe->setFat($nbIngredients * $fat);
            $recipe->getComments([]);
            $recipe->getRecipeSteps($arrStep);
            $recipe->setImageName('');
            $recipe->getRecipeFavorite([]);

            //RANDOM USER SELECTED
            $user = $manager->getRepository(User::class)->find(rand(1,31));
            $recipe->setUserRecipe($user);

            $manager->persist($recipe);
        }

        foreach ($this->getMockedRecipe() as $recipeMock) {
            $recipe = new Recipe();
            $recipe->setTitle($recipeMock['title']);

            $date = date_create_from_format('H:i:s',$faker->time());

            $recipe->setTime($date);
            $category = $manager->getRepository(Category::class)->findOneBy(['name' => $recipeMock['category']]);

            $category->setName($recipeMock['category']);
            $recipe->addCategory($category);
            $recipe->setCalory($recipeMock['calory']);
            $recipe->setProtein($recipeMock['protein']);
            $recipe->setCarbohydrate($recipeMock['carbohydrate']);
            $recipe->setFat($recipeMock['fat']);
            $recipe->setImageName('');

            foreach ($recipeMock['ingredients'] as $ingredientMock){
                $ingredient = new Ingredient();
                $ingredient->setName($ingredientMock['title']);
                $ingredient->setQuantity($ingredientMock['quantity']);
                $ingredient->setProtein($ingredientMock['protein']);
                $ingredient->setCarbohydrate($ingredientMock['carbohydrate']);
                $ingredient->setFat($ingredientMock['fat']);
                $ingredient->setMeasuringUnit($ingredientMock['measuringUnit']);
                $ingredient->setRecipe($recipe);
                $recipe->addIngredient($ingredient);
                $manager->persist($ingredient);
            }

            //RECIPE_STEPS
            foreach ($recipeMock['recipeSteps'] as $recipeStepMock) {
                $recipeStep = new RecipeStep();
                $recipeStep->setTitle($recipeStepMock['title']);
                $recipeStep->setStepNumber($recipeStepMock['stepNumber']);
                $recipeStep->setContent($recipeStepMock['content']);
                $recipeStep->setRecipe($recipe);
                $recipe->addRecipeStep($recipeStep);
                $manager->persist($recipeStep);
            }

            //RANDOM USER SELECTED
            $user = $manager->getRepository(User::class)->findOneBy(['email' => 'chloe@gmail.com']);
            $recipe->setUserRecipe($user);


        }

        foreach ($this->getMockedDyfitRecipe() as $recipeMock) {
            $recipe = new Recipe();
            $recipe->setTitle($recipeMock['title']);

            $date = date_create_from_format('H:i:s',$faker->time());

            $recipe->setTime($date);
            $category = $manager->getRepository(Category::class)->findOneBy(['name' => $recipeMock['category']]);

            $category->setName($recipeMock['category']);
            $recipe->addCategory($category);
            $recipe->setCalory($recipeMock['calory']);
            $recipe->setProtein($recipeMock['protein']);
            $recipe->setCarbohydrate($recipeMock['carbohydrate']);
            $recipe->setFat($recipeMock['fat']);
            $recipe->setImageName('');

            foreach ($recipeMock['ingredients'] as $ingredientMock){
                $ingredient = new Ingredient();
                $ingredient->setName($ingredientMock['title']);
                $ingredient->setQuantity($ingredientMock['quantity']);
                $ingredient->setProtein($ingredientMock['protein']);
                $ingredient->setCarbohydrate($ingredientMock['carbohydrate']);
                $ingredient->setFat($ingredientMock['fat']);
                $ingredient->setMeasuringUnit($ingredientMock['measuringUnit']);
                $ingredient->setRecipe($recipe);
                $recipe->addIngredient($ingredient);
                $manager->persist($ingredient);
            }

            //RECIPE_STEPS
            foreach ($recipeMock['recipeSteps'] as $recipeStepMock) {
                $recipeStep = new RecipeStep();
                $recipeStep->setTitle($recipeStepMock['title']);
                $recipeStep->setStepNumber($recipeStepMock['stepNumber']);
                $recipeStep->setContent($recipeStepMock['content']);
                $recipeStep->setRecipe($recipe);
                $recipe->addRecipeStep($recipeStep);
                $manager->persist($recipeStep);
            }

            //RANDOM USER SELECTED
            $user = $manager->getRepository(User::class)->findOneBy(['email' => 'dylan.correia@hotmail.com']);
            $recipe->setUserRecipe($user);


        }
        $manager->flush();

    }

    private function getMockedRecipe(){
        return [
            [
                'title' => 'GLACE PROTÉINÉE À LA FRAISE',
                'category' => 'Protéiné',
                'calory' => 96,
                'protein' => 10,
                'carbohydrate' => 4,
                'fat' => 4,
                'ingredients' => [
                    [
                        'title' => 'Lait demi-écrémé',
                        'quantity' => 70,
                        'protein' => 7,
                        'carbohydrate' => 10,
                        'fat' => 4,
                        'measuringUnit' => 'mL',
                    ],
                    [
                        'title' => 'Yaourt grecque',
                        'quantity' => 150,
                        'protein' => 12,
                        'carbohydrate' => 17,
                        'fat' => 5,
                        'measuringUnit' => 'g',
                    ],
                ],
                'recipeSteps' =>
                    [
                        [
                            'title'  => '',
                            'stepNumber' => '1',
                            'content' => 'Dans un mixeur, placer tous les ingrédients à l\'exception des fruits rouges et mixer jusqu\'à obtention d\'une préparation crémeuse. '
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '2',
                            'content' => 'Mettre les fruits rouges dans un moule de votre choix puis verser la préparation par-dessus.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '3',
                            'content' => 'Conseil : vous avez le batonnet foodspring chez vous ? Super ! Il est parfait pour cette recette.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '4',
                            'content' => 'Placer minimum 8 heures au congélateur puis retirer du moule.'
                        ],
                    ]
            ],
        ];
    }

    private function getMockedDyfitRecipe(){
        return [
            [
                'title' => 'Salade riche en protéine',
                'category' => 'Protéiné',
                'calory' => 575,
                'protein' => 38,
                'carbohydrate' => 8,
                'fat' => 41,
                'ingredients' => [
                    [
                        'title' => 'saumon fumé',
                        'quantity' => 180,
                        'protein' => 7,
                        'carbohydrate' => 10,
                        'fat' => 4,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Avocat',
                        'quantity' => 1,
                        'protein' => 12,
                        'carbohydrate' => 17,
                        'fat' => 5,
                        'measuringUnit' => 'piece',
                    ],
                    [
                        'title' => 'graines de sésame noir',
                        'quantity' => 5,
                        'protein' => 12,
                        'carbohydrate' => 17,
                        'fat' => 5,
                        'measuringUnit' => 'g',
                    ],
                ],
                'recipeSteps' =>
                    [
                        [
                            'title'  => '',
                            'stepNumber' => '1',
                            'content' => 'Coupez en petits morceaux le saumon, le demi concombre, la tomate et l’avocat. Placez le tout dans une assiette et laissez de côté.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '2',
                            'content' => 'Dans un bol, mélangez l’huile d’olive, le vinaigre, les graines de sésame et le basilic.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '3',
                            'content' => 'Ajoutez le contenu du bol sur les autres ingrédients et dégustez… Rapide, facile et idéal pour votre tour de taille.'
                        ],
                    ]
            ],
            [
                'title' => 'Glace protéiné à la fraise',
                'category' => 'Protéiné',
                'calory' => 96,
                'protein' => 10,
                'carbohydrate' => 4,
                'fat' => 4,
                'ingredients' => [
                    [
                        'title' => 'Lait demi-écrémé',
                        'quantity' => 70,
                        'protein' => 7,
                        'carbohydrate' => 10,
                        'fat' => 4,
                        'measuringUnit' => 'mL',
                    ],
                    [
                        'title' => 'Yaourt grecque',
                        'quantity' => 150,
                        'protein' => 12,
                        'carbohydrate' => 17,
                        'fat' => 5,
                        'measuringUnit' => 'g',
                    ],
                ],
                'recipeSteps' =>
                    [
                        [
                            'title'  => '',
                            'stepNumber' => '1',
                            'content' => 'Dans un mixeur, placer tous les ingrédients à l\'exception des fruits rouges et mixer jusqu\'à obtention d\'une préparation crémeuse. '
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '2',
                            'content' => 'Mettre les fruits rouges dans un moule de votre choix puis verser la préparation par-dessus.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '3',
                            'content' => 'Conseil : vous avez le batonnet foodspring chez vous ? Super ! Il est parfait pour cette recette.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '4',
                            'content' => 'Placer minimum 8 heures au congélateur puis retirer du moule.'
                        ],
                    ]
            ],
            [
                'title' => 'Gâteau au Double Chocolat & Banane',
                'category' => 'Protéiné',
                'calory' => 445,
                'protein' => 30,
                'carbohydrate' => 12,
                'fat' => 12,
                'ingredients' => [
                    [
                        'title' => 'Beurre de Cacahuète Croustillant',
                        'quantity' => 5,
                        'protein' => 7,
                        'carbohydrate' => 10,
                        'fat' => 4,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'farine',
                        'quantity' => 150,
                        'protein' => 12,
                        'carbohydrate' => 17,
                        'fat' => 5,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'banane',
                        'quantity' => 2,
                        'protein' => 12,
                        'carbohydrate' => 17,
                        'fat' => 5,
                        'measuringUnit' => 'piece',
                    ],
                    [
                        'title' => 'levure',
                        'quantity' => 5,
                        'protein' => 12,
                        'carbohydrate' => 17,
                        'fat' => 5,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'whey',
                        'quantity' => 20,
                        'protein' => 12,
                        'carbohydrate' => 17,
                        'fat' => 5,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'poudre de cacao',
                        'quantity' => 5,
                        'protein' => 12,
                        'carbohydrate' => 17,
                        'fat' => 5,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'lait',
                        'quantity' => 200,
                        'protein' => 12,
                        'carbohydrate' => 17,
                        'fat' => 5,
                        'measuringUnit' => 'ml',
                    ],
                    [
                        'title' => 'lait',
                        'quantity' => 1,
                        'protein' => 12,
                        'carbohydrate' => 17,
                        'fat' => 5,
                        'measuringUnit' => 'piece',
                    ],
                ],
                'recipeSteps' =>
                    [
                        [
                            'title'  => '',
                            'stepNumber' => '1',
                            'content' => 'Préchauffez le four à 180°'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '2',
                            'content' => 'Préchauffez le four à 180°'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '3',
                            'content' => 'Mélangez bien'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '4',
                            'content' => 'Ajoutez la farine, la levure, la protéine le cacao. Mélangez jusqu’à ce que votre pâte à brownie soit prête!'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '5',
                            'content' => 'Placez dans un plat à brownie sur du papier alimentaire'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '6',
                            'content' => 'Faites cuire pendant 20 à 25 minutes (enfoncé un couteau dedans , si il resort propre le gâteau est cuit)'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '7',
                            'content' => 'Ensuite, saupoudrez le tout de poudre de cacao et de tranche de banane'
                        ],
                    ]
            ],
        ];
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on user
     *
     * @return array
     */
    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}

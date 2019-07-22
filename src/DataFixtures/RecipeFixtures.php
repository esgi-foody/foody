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

        foreach ($this->getMockedRecipe2() as $recipeMock) {
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
            $user = $manager->getRepository(User::class)->findOneBy(['email' => 'karim@gmail.com']);
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

    private function getMockedRecipe2(){
        return [
            [
                'title' => 'Cookies vegan chocolat et raisins secs',
                'category' => 'Vegan',
                'calory' => 150,
                'protein' => 10,
                'carbohydrate' => 3,
                'fat' => 20,
                'ingredients' => [
                    [
                        'title' => 'Farine',
                        'quantity' => 175,
                        'protein' => 3,
                        'carbohydrate' => 7,
                        'fat' => 2,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Margarine végétale ramollie',
                        'quantity' => 100,
                        'protein' => 8,
                        'carbohydrate' => 10,
                        'fat' => 15,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Sucre de canne non raffiné',
                        'quantity' => 70,
                        'protein' => 3,
                        'carbohydrate' => 8,
                        'fat' => 12,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Pépites de chocolat noir',
                        'quantity' => 50,
                        'protein' => 3,
                        'carbohydrate' => 10,
                        'fat' => 9,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Raisins secs',
                        'quantity' => 50,
                        'protein' => 6,
                        'carbohydrate' => 8,
                        'fat' => 5,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Sachet de levure',
                        'quantity' => 1,
                        'protein' => 2,
                        'carbohydrate' => 4,
                        'fat' => 3,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Lait végétal de votre choix',
                        'quantity' => 20,
                        'protein' => 3,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'mL',
                    ],
                    [
                        'title' => 'Sel',
                        'quantity' => 5,
                        'protein' => 1,
                        'carbohydrate' => 3,
                        'fat' => 8,
                        'measuringUnit' => 'g',
                    ],
                ],
                'recipeSteps' =>
                    [
                        [
                            'title'  => '',
                            'stepNumber' => '1',
                            'content' => 'Dans un saladier, ajoutez tous vos ingrédients. '
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '2',
                            'content' => 'Mélangez-les à l\'aide d’une cuillère en bois puis avec vos mains.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '3',
                            'content' => 'Recouvrez de film étirable puis placez votre pâte au réfrigérateur 30 minutes à une heure.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '4',
                            'content' => 'Préchauffez votre four à 180°C.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '5',
                            'content' => 'A l\'aide d’une cuillère à soupe, formez une quinzaine de cookies.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '6',
                            'content' => 'Enfournez 20 à 25 minutes suivant la puissance de votre four et le résultat souhaité (plus ou moins moelleux).'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '7',
                            'content' => 'Laissez refroidir une dizaine de minutes avant de déguster vos cookies !'
                        ],
                    ]
            ],
            [
                'title' => 'Pho',
                'category' => 'Vietnamienne',
                'calory' => 100,
                'protein' => 50,
                'carbohydrate' => 20,
                'fat' => 10,
                'ingredients' => [
                    [
                        'title' => 'Blanc de poireau émincé',
                        'quantity' => 1,
                        'protein' => 5,
                        'carbohydrate' => 3,
                        'fat' => 2,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Sauce soja',
                        'quantity' => 20,
                        'protein' => 3,
                        'carbohydrate' => 4,
                        'fat' => 10,
                        'measuringUnit' => 'mL',
                    ],
                    [
                        'title' => 'Petit bouquet de coriandre ciselée',
                        'quantity' => 1,
                        'protein' => 4,
                        'carbohydrate' => 2,
                        'fat' => 2,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Oignon en lamelle',
                        'quantity' => 1,
                        'protein' => 3,
                        'carbohydrate' => 5,
                        'fat' => 2,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Gingembre râpé',
                        'quantity' => 20,
                        'protein' => 2,
                        'carbohydrate' => 3,
                        'fat' => 2,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Anis étoilé',
                        'quantity' => 1,
                        'protein' => 2,
                        'carbohydrate' => 4,
                        'fat' => 3,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Nouilles plates de riz',
                        'quantity' => 125,
                        'protein' => 3,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Germes de soja',
                        'quantity' => 125,
                        'protein' => 5,
                        'carbohydrate' => 3,
                        'fat' => 3,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Canelle en poudre',
                        'quantity' => 10,
                        'protein' => 3,
                        'carbohydrate' => 3,
                        'fat' => 3,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Boeuf émincé finement',
                        'quantity' => 600,
                        'protein' => 30,
                        'carbohydrate' => 3,
                        'fat' => 15,
                        'measuringUnit' => 'g',
                    ],
                ],
                'recipeSteps' =>
                    [
                        [
                            'title'  => '',
                            'stepNumber' => '1',
                            'content' => 'Dans un faitout d\'eau bouillante, jetez la viande, les oignons en lamelles, les épices et le blanc de poireaux.Laissez mijoter à feu doux pendant 3 heures.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '2',
                            'content' => 'Dans un autre faitout, portez de l\'eau à ébullition et jetez les nouilles pour quelques minutes de cuisson.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '3',
                            'content' => 'Égouttez-les et placez les nouilles et les germes de soja rincés dans le bouillon du premier faitout. Laissez mijoter 5 min.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '4',
                            'content' => 'Servez bien chaud avec de la coriandre et de la sauce soja.'
                        ],
                    ]
            ],
            [
                'title' => 'Poulet au curry et lait de coco',
                'category' => 'Indienne',
                'calory' => 150,
                'protein' => 60,
                'carbohydrate' => 15,
                'fat' => 25,
                'ingredients' => [
                    [
                        'title' => 'Filets de poulets',
                        'quantity' => 4,
                        'protein' => 50,
                        'carbohydrate' => 15,
                        'fat' => 15,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Echalotes',
                        'quantity' => 4,
                        'protein' => 3,
                        'carbohydrate' => 4,
                        'fat' => 4,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Lait de coco',
                        'quantity' => 20,
                        'protein' => 4,
                        'carbohydrate' => 2,
                        'fat' => 2,
                        'measuringUnit' => 'mL',
                    ],
                    [
                        'title' => 'Poudre de curry',
                        'quantity' => 20,
                        'protein' => 3,
                        'carbohydrate' => 5,
                        'fat' => 2,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Farine',
                        'quantity' => 10,
                        'protein' => 2,
                        'carbohydrate' => 3,
                        'fat' => 2,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Huile d\'olive',
                        'quantity' => 15,
                        'protein' => 2,
                        'carbohydrate' => 4,
                        'fat' => 3,
                        'measuringUnit' => 'mL',
                    ],
                    [
                        'title' => 'Piment',
                        'quantity' => 1,
                        'protein' => 3,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'pièce(s)',
                    ],
                ],
                'recipeSteps' =>
                    [
                        [
                            'title'  => '',
                            'stepNumber' => '1',
                            'content' => 'Faire revenir le poulet en petits morceaux environ 5 minutes, réserver.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '2',
                            'content' => 'Dans le reste d\'huile, faire revenir les échalotes ciselées. Ajouter farine et poudre de curry, cuire 1 minute.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '3',
                            'content' => 'Ajouter petit à petit lait de coco en remuant, faire bouillir.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '4',
                            'content' => 'Remettre le poulet, ajouter piment et sel selon le goût. Mettre à feu doux/moyen pendant 20 minutes environ.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '5',
                            'content' => 'Dégustez !'
                        ],
                    ]
            ],
            [
                'title' => 'Paella Espagnole',
                'category' => 'Espagnole',
                'calory' => 155,
                'protein' => 45,
                'carbohydrate' => 20,
                'fat' => 30,
                'ingredients' => [
                    [
                        'title' => 'Langoustines',
                        'quantity' => 12,
                        'protein' => 50,
                        'carbohydrate' => 15,
                        'fat' => 15,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Cuisses de poulet',
                        'quantity' => 4,
                        'protein' => 20,
                        'carbohydrate' => 4,
                        'fat' => 15,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Chorizo',
                        'quantity' => 1,
                        'protein' => 10,
                        'carbohydrate' => 2,
                        'fat' => 15,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Poivron vert',
                        'quantity' => 1,
                        'protein' => 3,
                        'carbohydrate' => 5,
                        'fat' => 2,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Poivron rouge',
                        'quantity' => 1,
                        'protein' => 2,
                        'carbohydrate' => 3,
                        'fat' => 2,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Poivron jaune',
                        'quantity' => 1,
                        'protein' => 2,
                        'carbohydrate' => 4,
                        'fat' => 3,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Tomates',
                        'quantity' => 4,
                        'protein' => 3,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Moules',
                        'quantity' => 700,
                        'protein' => 10,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Encornets',
                        'quantity' => 500,
                        'protein' => 10,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Riz rond',
                        'quantity' => 400,
                        'protein' => 3,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Petit pois',
                        'quantity' => 400,
                        'protein' => 3,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Eau chaude',
                        'quantity' => 1,
                        'protein' => 3,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'L',
                    ],
                    [
                        'title' => 'Gousses d\'ail',
                        'quantity' => 4,
                        'protein' => 3,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'pièce(s)',
                    ],
                    [
                        'title' => 'Huile d\'olive',
                        'quantity' => 15,
                        'protein' => 3,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'mL',
                    ],
                    [
                        'title' => 'Safran',
                        'quantity' => 15,
                        'protein' => 3,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Curry',
                        'quantity' => 15,
                        'protein' => 3,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Sel',
                        'quantity' => 5,
                        'protein' => 3,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'g',
                    ],
                    [
                        'title' => 'Poivre',
                        'quantity' => 5,
                        'protein' => 3,
                        'carbohydrate' => 1,
                        'fat' => 4,
                        'measuringUnit' => 'g',
                    ],
                ],
                'recipeSteps' =>
                    [
                        [
                            'title'  => '',
                            'stepNumber' => '1',
                            'content' => 'Couper les cuisses de poulet en 2 morceaux. Réserver.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '2',
                            'content' => 'Faire cuire les encornets dans l\'eau bouillante 6 minutes environ, piquer pour vérifier la cuisson (comme pour les patates). Réserver'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '3',
                            'content' => 'Faire cuire les langoustines 5 minutes dans l\'eau bouillante. Réserver.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '4',
                            'content' => 'Dans une casserole, faire bouillir de l\'eau y mettre les petits pois quand l\'eau reprend l\'ébullition, réduire à feu moyen 8-10 min. Egoutter. Réserver.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '5',
                            'content' => 'Dans une cocotte, faire ouvrir les moules à feu vif (variante on peut y ajouter 1 verre de vin blanc) 5-6 minutes. Réserver les moules dans leur jus.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '6',
                            'content' => 'Couper les encornets en rondelles. Réserver'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '7',
                            'content' => 'Couper les tomates, les vider et les couper en morceaux (moyen). Réserver.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '8',
                            'content' => 'Couper les poivrons comme les tomates (enlever les pépins). Réserver.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '9',
                            'content' => 'Couper l\'ail finement. Réserver.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '10',
                            'content' => 'Préparer sel, poivre, huile d\'olive, safran et curry. Dans une poêle à paella (ou un wok fera l\'affaire) verser l\'huile d\'olive. Quand l\'huile frémit, y mettre les morceaux de poulet et les faire bien dorer de tout les côtés.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '11',
                            'content' => 'Une fois bien dorés, ajouter les encornets, le chorizo, l\'ail, les poivrons et les tomates.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '12',
                            'content' => 'Ajouter le litre d\'eau chaude (variante : on peut y mettre un bouillon de poule avant). Filtrer le jus des moules et ajouter au plat 2 à 3 verres de ce jus (selon le goût). Saler, poivrer, saupoudrer le curry sur toute la surface du plat. Mélanger. Laissez cuire 15 minutes à feux moyen.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '13',
                            'content' => 'Ajouter le riz, le safran, les moules, les langoustine. Cuire encore 20-25 minutes.'
                        ],
                        [
                            'title'  => '',
                            'stepNumber' => '14',
                            'content' => 'Ajouter les petits pois chauds. Laisser reposer 5 minutes ou servir chaud.'
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

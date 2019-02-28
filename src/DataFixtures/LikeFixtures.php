<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Like;
use App\Entity\User;


class LikeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 1000; $i++) {
            $userId = rand(1,31);
            $recipeId = rand(1,100);
            $uniqCombinaison[$userId][$recipeId] = '';

        }

        for($i = 1; $i<= 31; $i++)
            for($x = 1; $x<= 100; $x++)
                if(!isset($array[$i][$x])){
                    $like = new Like();
                    $like->setLiker($manager->getRepository(User::class)->find($i));
                    $like->setRecipe($manager->getRepository(Recipe::class)->find($x));
                    $manager->persist($like);
                }

        $manager->flush();

    }
    public function getDependencies()
    {
        return [UserFixtures::class , RecipeFixtures::class];
    }
}

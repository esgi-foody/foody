<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Favorite;
use App\Entity\User;


class FavoriteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 100; $i++) {

            $favorite = new Favorite();
            $favorite->setUserFavorite($manager->getRepository(User::class)->find(rand(1,31)));
            $favorite->setRecipe($manager->getRepository(Recipe::class)->find(rand(1,100)));

            $manager->persist($favorite);
            $manager->flush();
        }
    }
    public function getDependencies()
    {
        return [UserFixtures::class , RecipeFixtures::class];
    }
}

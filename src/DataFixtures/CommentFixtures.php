<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Recipe;

use Faker;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 300; $i++) {
            $comment = new Comment();
            $comment->setRecipe($manager->getRepository(Recipe::class)->find(rand(1,100)));
            $comment->setCommentator($manager->getRepository(User::class)->find(rand(1,30)));
            $comment->setData($faker->sentence($nbWords = 6, $variableNbWords = true));
            $manager->persist($comment);

            $manager->flush();
        }
    }
    public function getDependencies()
    {
        return [UserFixtures::class, RecipeFixtures::class];
    }

}

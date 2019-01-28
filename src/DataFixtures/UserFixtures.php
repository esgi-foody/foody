<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Faker;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setPseudo($faker->firstName . " " . $faker->lastName);
            $user->setPassword($faker->password);
            $user->setDateOfBirth($faker->dateTime);
            $user->setPathImg($faker->imageUrl(640, 480));
            $user->setEmail($faker->email);
            $user->setStatus(rand(0,1));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);

            $manager->flush();
        }
    }
}

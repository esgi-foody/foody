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

        for ($i = 0; $i < 30; $i++) {
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setPseudo($faker->firstName . " " . $faker->lastName);
            $user->setPassword('Foody2019');
            $user->setDateOfBirth($faker->dateTime);
            $user->setPathImg($faker->imageUrl( 480, 480 , 'people'));
            $user->setEmail($faker->email);
            $user->setStatus(rand(0,1));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);


        }

        $user = new User();
        $user->setUsername('chloe75');
        $user->setPseudo('Chloe Fit');
        $user->setPassword('Foody2019');
        $user->setDateOfBirth($faker->dateTime);
        $user->setPathImg($faker->imageUrl( 480, 480 , 'people'));
        $user->setEmail('chloe.fit-girl@gmail.com');
        $user->setStatus('1');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $manager->flush();
    }
}

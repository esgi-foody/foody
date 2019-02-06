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
            $user->setPassword($faker->password);
            $user->setDateOfBirth($faker->dateTime);
            $user->setPathImg($faker->imageUrl(640, 480));
            $user->setEmail($faker->email);
            $user->setStatus(1);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        $user = new User();
        $user->setUsername('chloe');
        $user->setPseudo('Chloe fit');
        $user->setPassword('$2y$13$13d5PNpmneww1yehhoSUweJfVdDRTGQqnjbi8Vxs9d1WY3AloZRFW');
        $user->setDateOfBirth($faker->dateTime);
        $user->setPathImg($faker->imageUrl(640, 480));
        $user->setEmail('chloe@gmail.com');
        $user->setStatus(1);
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);


        $user = new User();
        $user->setUsername('root');
        $user->setPseudo('admin foody');
        $user->setPassword('$2y$13$dXOe6srZN/F1eMItPegMgezvWHVOkNVY67goEGncTVXE6YFf2EvCu');
        $user->setDateOfBirth($faker->dateTime);
        $user->setPathImg($faker->imageUrl(640, 480));
        $user->setEmail('root@gmail.com');
        $user->setStatus(1);
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 30; $i++) {
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setPseudo($faker->firstName . " " . $faker->lastName);
            $password = $this->encoder->encodePassword($user, 'Foody2019');
            $user->setPassword($password);
            $user->setDateOfBirth($faker->dateTime);
            $user->setImageName('');
            $user->setEmail($faker->email);
            $user->setStatus(1);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        $user = new User();
        $user->setUsername('chloe');
        $user->setPseudo('Chloe fit');
        $password = $this->encoder->encodePassword($user, 'Chloe2019');
        $user->setPassword($password);
        $user->setDateOfBirth($faker->dateTime);
        $user->setImageName('');
        $user->setEmail('chloe@gmail.com');
        $user->setStatus(1);
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('dyfit');
        $user->setPseudo('Dy fit');
        $password = $this->encoder->encodePassword($user, 'Root2019');
        $user->setPassword($password);
        $user->setDateOfBirth($faker->dateTime);
        $user->setImageName('');
        $user->setEmail('dylan.correia@hotmail.com');
        $user->setStatus(1);
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('root');
        $user->setPseudo('admin foody');
        $password = $this->encoder->encodePassword($user, 'Root2019');
        $user->setPassword($password);
        $user->setDateOfBirth($faker->dateTime);
        $user->setImageName('');
        $user->setEmail('root@gmail.com');
        $user->setStatus(1);
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();
    }
}

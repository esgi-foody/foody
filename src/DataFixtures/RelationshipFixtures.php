<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Relationship;
use App\Entity\User;
use Faker;

class RelationshipFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 200; $i++) {
            $relationship = new Relationship();
            $relationship->setFollowed($manager->getRepository(User::class)->find(rand(1,31)));
            $relationship->setFollower($manager->getRepository(User::class)->find(rand(1,31)));
            $manager->persist($relationship);
        }
        $manager->flush();

    }
}

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;
use Faker;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        $categories = [ 'Italienne','Americaine','Française','Indienne','Chinoise',
                        'Thailandaise','Vietnamienne','Epicée','Japonaise','Protéiné',
                        'Végétarien','Léger','Vegan','Gastronomique','Poisson',
                        'Légumes','Espagnole','Soupe','Entrée','Plat'];
        for ($i = 0; $i < 20; $i++) {
            $category = new Category();
            $category->setName($categories[$i]);
            $category->setHexaColor($faker->hexcolor);
            $manager->persist($category);
        }

        $manager->flush();

    }
}

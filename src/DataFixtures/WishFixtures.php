<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WishFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('en_EN');

        for ($i = 0; $i < 100; $i++) {
            $wish = new Wish();
            $wish->setTitle($faker->realText(30));
            $wish->setDescription($faker->realText(400, true));
            $wish->setAuthor($faker->name());
            $wish->setDateUpdated(new \DateTime());
            $wish->setIsPublished($faker->boolean(75));

            $manager->persist($wish);
        }

        $manager->flush();
    }
}

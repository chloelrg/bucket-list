<?php

namespace App\DataFixtures;

use App\Factory\WishFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        WishFactory::createMany(100);
        $manager->flush();
    }
}

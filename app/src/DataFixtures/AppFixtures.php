<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Item 1
        $item1 = new Item();
        $item1->setName('Sword of Fire');
        $item1->setDescription('A flaming sword that can burn enemies with just a touch.');
        $manager->persist($item1);

        // Item 2
        $item2 = new Item();
        $item2->setName('Ice Shield');
        $item2->setDescription('A shield covered with eternal ice, can protect against any fire.');
        $manager->persist($item2);

        // Item 3
        $item3 = new Item();
        $item3->setName('Elixir of health');
        $item3->setDescription('Restores the character\'s health by 50 units.');
        $manager->persist($item3);

        // Item 4
        $item4 = new Item();
        $item4->setName('Cloak of Invisibility');
        $item4->setDescription('Makes the wearer invisible for a short time.');
        $manager->persist($item4);

        // Save changes
        $manager->flush();
    }
}


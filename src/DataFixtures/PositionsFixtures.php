<?php

namespace App\DataFixtures;

use App\Entity\ClassTypes;
use App\Entity\Positions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PositionsFixtures extends Fixture
{
    private const array POSITIONS = [
        ['technik', 'tech.', 240, 'asystent'],
        ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::POSITIONS as $value) {
            $ct = (new Positions())
                ->setTitle($value[0])
                ->setAbbreviation($value[1])
                ->setPensum($value[2])
                ->setDescription($value[3]);
            $manager->persist($ct);
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\ClassTypes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClassTypesFixtures extends Fixture
{
    private const array CLASS_TYPES = [
            'CW' => 'Ćwiczenia',
            'KON' => 'Konwersatoria',
            'LAB' => 'Laboratoria',
            'SEM' => 'Seminaria',
            'WYK' => 'Wykłady',
            'WAR' => 'Warsztat'
        ];

    public function load(ObjectManager $manager): void
    {
        foreach(self::CLASS_TYPES as $key => $value) {
            $ct = (new ClassTypes())
                ->setType($value)
                ->setAbbreviation($key);
            $manager->persist($ct);
        }

        $manager->flush();
    }
}

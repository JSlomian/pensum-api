<?php

namespace App\DataFixtures;

use App\Entity\AttendanceModes;
use App\Entity\EducationLevels;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EducationLevelFixtures extends Fixture
{
    private const array EDUCATION_LEVELS = [
            [
                'name'         => 'Studia Pierwszego Stopnia',
                'abbreviation' => 'SPS',
            ],
            [
                'name'         => 'Studia Drugiego Stopnia',
                'abbreviation' => 'SDS',
            ]
        ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::EDUCATION_LEVELS as $data) {
            $educationLevel = new EducationLevels();
            $educationLevel
                ->setName($data['name'])
                ->setAbbreviation($data['abbreviation']);
            $manager->persist($educationLevel);
        }

        $manager->flush();
    }
}

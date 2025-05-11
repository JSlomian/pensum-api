<?php

namespace App\DataFixtures;

use App\Entity\AttendanceModes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AttendanceModesFixtures extends Fixture
{
    private const array ATTENDANCE_MODES = [
        [
            'name' => 'stacjonarne',
            'abbreviation' => 'S',
        ],
        [
            'name' => 'wieczorowe',
            'abbreviation' => 'W',
        ],
        [
            'name' => 'niestacjonarne',
            'abbreviation' => 'N',
        ],
        [
            'name' => 'blended-learning stacjonarne',
            'abbreviation' => 'BL-S',
        ],
        [
            'name' => 'blended-learning niestacjonarne',
            'abbreviation' => 'BL-N',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ATTENDANCE_MODES as $data) {
            $position = new AttendanceModes();
            $position
                ->setName($data['name'])
                ->setAbbreviation($data['abbreviation']);
            $manager->persist($position);
        }

        $manager->flush();
    }
}

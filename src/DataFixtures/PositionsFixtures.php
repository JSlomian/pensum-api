<?php

namespace App\DataFixtures;

use App\Entity\Positions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PositionsFixtures extends Fixture
{
    private const array POSITIONS = [
        [
            'description' => 'asystent',
            'abbreviation' => 'tech.',
            'title' => 'techik',
            'pensum' => 240,
        ],
        [
            'description' => 'Wykładowca',
            'abbreviation' => 'lic.',
            'title' => 'licencjat',
            'pensum' => 360,
        ],
        [
            'description' => 'starszy wykładowca',
            'abbreviation' => 'inż.',
            'title' => 'inżynier',
            'pensum' => 360,
        ],
        [
            'description' => 'adiunkt',
            'abbreviation' => 'mgr.',
            'title' => 'magister',
            'pensum' => 240,
        ],
        [
            'description' => 'adiunkt* obniżenie 50%',
            'abbreviation' => 'mgr inż.',
            'title' => 'magister inżynier',
            'pensum' => 120,
        ],
        [
            'description' => 'profesor zwyczajny',
            'abbreviation' => 'doc.',
            'title' => 'docent',
            'pensum' => 180,
        ],
        [
            'description' => 'profesor wizytujący',
            'abbreviation' => 'dr',
            'title' => 'doktor',
            'pensum' => 210,
        ],
        [
            'description' => 'profesor* obniżenie 50%',
            'abbreviation' => 'dr inż',
            'title' => 'doktor inżynier',
            'pensum' => 105,
        ],
        [
            'description' => '2/3 profesor',
            'abbreviation' => 'dr hab.',
            'title' => 'doktor habilitowany',
            'pensum' => 120,
        ],
        [
            'description' => '3/4 wykładowca',
            'abbreviation' => 'dr hab. inż',
            'title' => 'doktor inżynier habilitowany magister',
            'pensum' => 270,
        ],
        [
            'description' => 'Dziekan Adiunkt',
            'abbreviation' => 'prof. nadzw.',
            'title' => 'profesor nadzwyczajny',
            'pensum' => 160,
        ],
        [
            'description' => 'umowa-zlecenie',
            'abbreviation' => 'prof.',
            'title' => 'profesor',
            'pensum' => null,
        ],
        [
            'description' => '1/4 etatu profesor',
            'abbreviation' => 'prof. dr hab.',
            'title' => 'profesor doktor habilitowany',
            'pensum' => 45,
        ],
        [
            'description' => 'profesor nadzwyczajny',
            'abbreviation' => 'prof. dr hab. inż.',
            'title' => 'profesor doktor inżynier habilitowany magister',
            'pensum' => 210,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::POSITIONS as $data) {
            $position = new Positions();
            $position
                ->setDescription($data['description'])
                ->setAbbreviation($data['abbreviation'])
                ->setTitle($data['title'])
                ->setPensum($data['pensum']);

            $manager->persist($position);
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\ClassTypes;
use App\Entity\Institutes;
use App\Entity\Positions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InstitutesFixtures extends Fixture
{
    private const array INSTITUTES = [
        "ZTB" => "Zakład Teorii Bezpieczeństwa",
        "ZBW" => "Zakład Bezpieczeństwa Wewnętrznego",
        "ZOB" => "Zakład Ochrony Narodowej",
        "ZZK" => "Zakład Zarządzania Kryzysowego",
        "IHiP" => "Instytut Historii i Politologii",
        "ZOZ" => "Zakład Organizacji i Zarządzania",
        "IBN" => "Instytut Nauki o Zdrowiu",
        "SOC" => "Socjologia",
        "IGISr" => "Geografia",
        "IM" => "Instytut Matematyki",
        "KAD" => "Katedra Administracji",
        "ZBC" => "Zakład Bezpieczeństwa Cyberprzestrzeni",
        "ZL" => "Zakład Logistyki",
        "ZS" => "Zakład Socjologii",
        "ZEIF" => "Zakład Ekonomii i Finansów",
        "ZP" => "Zakład Politologii"
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::INSTITUTES as $key => $value) {
            $ct = (new Institutes())
                ->setName($value)
                ->setAbbreviation($key);
            $manager->persist($ct);
        }

        $manager->flush();
    }
}

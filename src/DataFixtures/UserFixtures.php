<?php

namespace App\DataFixtures;

use App\Entity\Institutes;
use App\Entity\Positions;
use App\Entity\User;
use App\Repository\InstitutesRepository;
use App\Repository\PositionsRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly PositionsRepository $positions,
        private readonly InstitutesRepository $institutes,
    ) {
    }

    private const array USERS = [
        [
            'email' => 'user@user.com',
            'first_name' => 'user',
            'last_name' => 'user',
            'password' => 'user',
            'roles' => [],
        ],
        [
            'email' => 'admin@admin.com',
            'first_name' => 'admin',
            'last_name' => 'admin',
            'password' => 'admin',
            'roles' => ['ROLE_ADMIN'],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        /** @var \App\Entity\Position $pos */
        $pos = $this->getReference('position', Positions::class);
        /** @var \App\Entity\Institute $ins */
        $ins = $this->getReference('institute', Institutes::class);
        foreach (self::USERS as $data) {
            $user = new User();
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $data['password']
            );
            $user
                ->setEmail($data['email'])
                ->setFirstName($data['first_name'])
                ->setLastName($data['last_name'])
                ->setPosition($pos)
                ->setInstitute($ins)
                ->setPassword($hashedPassword)
                ->setRoles($data['roles']);
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PositionsFixtures::class,
            InstitutesFixtures::class,
        ];
    }
}

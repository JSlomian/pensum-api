<?php

namespace App\DataFixtures;

use App\Entity\AttendanceModes;
use App\Entity\EducationLevels;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    private const array USERS = [
        [
            'email' => 'user@user.com',
            'password' => 'user',
            'roles' => []
        ],
        [
            'email' => 'admin@admin.com',
            'password' => 'admin',
            'roles' => ['ROLE_ADMIN']
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $data) {
            $user = new User();
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $data['password']
            );
            $user
                ->setEmail($data['email'])
                ->setPassword($hashedPassword)
                ->setRoles($data['roles']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}

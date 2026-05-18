<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => '123456',
            ],
            [
                'name' => 'auxiliar',
                'email' => 'auxiliar@gmail.com',
                'roles' => ['ROLE_AUXILIAR'],
                'password' => '123456',
            ],
            [
                'name' => 'dentista',
                'email' => 'dentista@gmail.com',
                'roles' => ['ROLE_DENTIST'],
                'password' => '123456',
            ],
            [
                'name' => 'lector',
                'email' => 'lector@gmail.com',
                'roles' => ['ROLE_LECTOR'],
                'password' => '123456',
            ],
        ];

        foreach ($users as $data) {
            $user = new User();
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));

            $manager->persist($user);
        }

        $manager->flush();
    }
}

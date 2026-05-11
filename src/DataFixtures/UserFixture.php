<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => '$2y$13$m8EGT456LdOtZQ6RuM9cJO3CxVTwqMsiEJcTlQMPVJ1xScAYU.ovK',
            ],
            [
                'name' => 'auxiliar',
                'email' => 'auxiliar@gmail.com',
                'roles' => ['ROLE_AUXILIAR'],
                'password' => '$2y$13$m8EGT456LdOtZQ6RuM9cJO3CxVTwqMsiEJcTlQMPVJ1xScAYU.ovK',
            ],
        ];

        foreach ($users as $data) {
            $user = new User();
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $user->setPassword($data['password']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}

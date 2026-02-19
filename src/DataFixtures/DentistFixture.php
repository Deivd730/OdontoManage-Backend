<?php

namespace App\DataFixtures;

use App\Entity\Dentist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DentistFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $dentist = new Dentist();
        $dentist->setEmail('admin@test.com');
        $dentist->setFirstName('Admin');
        $dentist->setLastName('User');
        $dentist->setRoles(['ROLE_USER']);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $dentist,
            '123456'
        );

        $dentist->setPassword($hashedPassword);

        $manager->persist($dentist);
        $manager->flush();
    }
}

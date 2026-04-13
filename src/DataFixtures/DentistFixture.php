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
        // Los dentists se cargan desde la migración inicial
        // Este fixture podría usarse para agregar más dentists dinámicamente
        // Por ahora está vacío ya que están en la migración
    }
}

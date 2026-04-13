<?php

namespace App\DataFixtures;

use App\Entity\Treatment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TreatmentFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Los treatments se cargan desde la migración inicial
        // Este fixture podría usarse para agregar más treatments dinámicamente
        // Por ahora está vacío ya que están en la migración
    }
}


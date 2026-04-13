<?php

namespace App\DataFixtures;

use App\Entity\Pathology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PathologyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Las pathologies se cargan desde la migración inicial
        // Este fixture podría usarse para agregar más pathologies dinámicamente
        // Por ahora está vacío ya que están en la migración
    }
}


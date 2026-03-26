<?php

namespace App\DataFixtures;

use App\Entity\Pathology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PathologyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $pathologies = [
            ['name' => 'Caries', 'color' => '#FF4136'],
            ['name' => 'Caries', 'color' => '#6f36ffff'],
            ['name' => 'Obturacion', 'color' => '#d96900ff'],
            ['name' => 'Obturacion', 'color' => '#0074D9'],
            ['name' => 'Corona', 'color' => '#ff5e00ff'],
            ['name' => 'Corona', 'color' => '#4400ffff'],
            ['name' => 'Ausente', 'color' => '#0c0000ff'],
            ['name' => 'Endodoncia', 'color' => '#420dc9ff'],
            ['name' => 'Endodoncia', 'color' => '#c90d0dff'],
            ['name' => 'Exodoncia', 'color' => '#111111ff'],
            ['name' => 'Exodonciaort', 'color' => '#3813f1ff'],
            ['name' => 'Exodonciaort', 'color' => '#761313ff'],
            ['name' => 'cariesX', 'color' => '#0c902fff'],
            ['name' => 'fisuras', 'color' => '#c2d814ff'],
            ['name' => 'puente', 'color' => '#0c0e01ff'],
        ];

        foreach ($pathologies as $data) {
            $pathology = new Pathology();
            $pathology->setDescription($data['name']);
            $pathology->setColor($data['color']);
            $manager->persist($pathology);
        }

        $manager->flush();
    }
}

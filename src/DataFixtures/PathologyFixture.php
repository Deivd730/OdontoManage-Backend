<?php

namespace App\DataFixtures;

use App\Entity\Pathology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PathologyFixture extends Fixture
{
    public const PATHOLOGY_CARIES = 'pathology_caries';
    public const PATHOLOGY_OBTURACION = 'pathology_obturacion';
    public const PATHOLOGY_CARIES_RADIOGRAFIA = 'pathology_caries_radiografia';
    public const PATHOLOGY_AUSENCIA_NATURAL = 'pathology_ausencia_natural';
    public const PATHOLOGY_SELLADO = 'pathology_sellado';

    public function load(ObjectManager $manager): void
    {
        $pathologies = [
            [
                'description' => 'Caries',
                'color' => '#f81307ff',
                'reference' => self::PATHOLOGY_CARIES,
            ],
            [
                'description' => 'Obturacion',
                'color' => '#0074D9',
                'reference' => self::PATHOLOGY_OBTURACION,
            ],
            [
                'description' => 'Caries vista en radiografia',
                'color' => '#07fd24ff',
                'reference' => self::PATHOLOGY_CARIES_RADIOGRAFIA,
            ],
            [
                'description' => 'Ausencia Natural',
                'color' => '#000000ff',
                'reference' => self::PATHOLOGY_AUSENCIA_NATURAL,
            ],
            [
                'description' => 'Sellado de fosas y fisuras',
                'color' => '#f9f914ff',
                'reference' => self::PATHOLOGY_SELLADO,
            ],
        ];

        foreach ($pathologies as $data) {
            $pathology = new Pathology();
            $pathology->setDescription($data['description']);
            $pathology->setColor($data['color']);

            $manager->persist($pathology);
            $this->addReference($data['reference'], $pathology);
        }

        $manager->flush();
    }
}


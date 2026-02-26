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
            'Caries',
            'Obturación (empaste)',
            'Corona',
            'Endodoncia (tratamiento de conducto)',
            'Extracción',
            'Fractura',
            'Ausente',
            'Implante',
            'Prótesis fija',
            'Prótesis removible',
            'Puente',
            'Diente incluido',
            'Diente en erupción',
            'Sellante',
            'Diastema',
            'Apiñamiento',
            'Abrasión',
            'Erosión',
            'Abfracción',
            'Mancha',
            'Gingivitis',
            'Periodontitis',
            'Cálculo (sarro)',
            'Placa bacteriana',
            'Sensibilidad',
            'Movilidad',
            'Fístula',
            'Absceso',
            'Quiste',
            'Lesión periapical',
        ];

        foreach ($pathologies as $description) {
            $pathology = new Pathology();
            $pathology->setDescription($description);
            $manager->persist($pathology);
        }

        $manager->flush();
    }
}

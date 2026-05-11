<?php

namespace App\DataFixtures;

use App\Entity\Treatment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TreatmentFixture extends Fixture
{
    public const TREATMENT_OBTURACION = 'treatment_obturacion';
    public const TREATMENT_EXODONCIA = 'treatment_exodoncia';
    public const TREATMENT_PUENTE = 'treatment_puente';
    public const TREATMENT_CORONA = 'treatment_corona';
    public const TREATMENT_ENDODONCIA = 'treatment_endodoncia';

    public function load(ObjectManager $manager): void
    {
        $treatments = [
            [
                'name' => 'Obturación',
                'description' => 'Restauracion de caries',
                'duration_minutes' => 40,
                'reference' => self::TREATMENT_OBTURACION,
            ],
            [
                'name' => 'Exodoncia',
                'description' => 'Extracción de piezas dentales',
                'duration_minutes' => 30,
                'reference' => self::TREATMENT_EXODONCIA,
            ],
            [
                'name' => 'Puente',
                'description' => 'Restauración de dientes perdidos',
                'duration_minutes' => 60,
                'reference' => self::TREATMENT_PUENTE,
            ],
            [
                'name' => 'Corona',
                'description' => 'Restauración de dientes con corona',
                'duration_minutes' => 50,
                'reference' => self::TREATMENT_CORONA,
            ],
            [
                'name' => 'Endodoncia',
                'description' => 'Tratamiento de conductos',
                'duration_minutes' => 90,
                'reference' => self::TREATMENT_ENDODONCIA,
            ],
        ];

        foreach ($treatments as $data) {
            $treatment = new Treatment();
            $treatment->setName($data['name']);
            $treatment->setDescription($data['description']);
            $treatment->setDurationMinutes($data['duration_minutes']);

            $manager->persist($treatment);
            $this->addReference($data['reference'], $treatment);
        }

        $manager->flush();
    }
}


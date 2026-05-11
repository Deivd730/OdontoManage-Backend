<?php

namespace App\DataFixtures;

use App\Entity\Dentist;
use App\Entity\Treatment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DentistTreatmentFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $relations = [
            [DentistFixture::DENTIST_ANA, TreatmentFixture::TREATMENT_OBTURACION],
            [DentistFixture::DENTIST_LUIS, TreatmentFixture::TREATMENT_EXODONCIA],
            [DentistFixture::DENTIST_MARTA, TreatmentFixture::TREATMENT_PUENTE],
            [DentistFixture::DENTIST_PEDRO, TreatmentFixture::TREATMENT_CORONA],
            [DentistFixture::DENTIST_JAVIER, TreatmentFixture::TREATMENT_ENDODONCIA],
            [DentistFixture::DENTIST_JORGE, TreatmentFixture::TREATMENT_OBTURACION],
            [DentistFixture::DENTIST_SOFIA, TreatmentFixture::TREATMENT_EXODONCIA],
            [DentistFixture::DENTIST_CARLOS, TreatmentFixture::TREATMENT_PUENTE],
            [DentistFixture::DENTIST_LAURA, TreatmentFixture::TREATMENT_CORONA],
            [DentistFixture::DENTIST_PEPE, TreatmentFixture::TREATMENT_ENDODONCIA],
        ];

        foreach ($relations as [$dentistRef, $treatmentRef]) {
            $dentist = $this->getReference($dentistRef, Dentist::class);
            $treatment = $this->getReference($treatmentRef, Treatment::class);
            $dentist->addTreatment($treatment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DentistFixture::class,
            TreatmentFixture::class,
        ];
    }
}

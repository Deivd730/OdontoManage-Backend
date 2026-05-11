<?php

namespace App\DataFixtures;

use App\Entity\Odontogram;
use App\Entity\Tooth;
use App\Entity\ToothTreatment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ToothTreatmentFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $records = [
            [OdontogramFixture::ODONTOGRAM_JAN, ToothFixture::TOOTH_11],
            [OdontogramFixture::ODONTOGRAM_FEB, ToothFixture::TOOTH_36],
            [OdontogramFixture::ODONTOGRAM_MAR, ToothFixture::TOOTH_21],
            [OdontogramFixture::ODONTOGRAM_APR, ToothFixture::TOOTH_46],
            [OdontogramFixture::ODONTOGRAM_MAY, ToothFixture::TOOTH_11],
            [OdontogramFixture::ODONTOGRAM_JUN, ToothFixture::TOOTH_14],
            [OdontogramFixture::ODONTOGRAM_JUL, ToothFixture::TOOTH_26],
            [OdontogramFixture::ODONTOGRAM_AUG, ToothFixture::TOOTH_31],
            [OdontogramFixture::ODONTOGRAM_SEP, ToothFixture::TOOTH_41],
            [OdontogramFixture::ODONTOGRAM_OCT, ToothFixture::TOOTH_18],
            [OdontogramFixture::ODONTOGRAM_NOV, ToothFixture::TOOTH_12],
            [OdontogramFixture::ODONTOGRAM_DEC, ToothFixture::TOOTH_51],
        ];

        foreach ($records as [$odontogramRef, $toothRef]) {
            $tooth = $this->getReference($toothRef, Tooth::class);

            $treatment = new ToothTreatment();
            $treatment->setOdontogram($this->getReference($odontogramRef, Odontogram::class));
            $treatment->setTreatment($this->getReference(TreatmentFixture::TREATMENT_OBTURACION, \App\Entity\Treatment::class));
            $treatment->setToothNumber($tooth->getToothNumber());
            $treatment->setToothFace(1);
            $treatment->setStatus(ToothTreatment::STATUS_DONE);

            $manager->persist($treatment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OdontogramFixture::class,
            ToothFixture::class,
            TreatmentFixture::class,
        ];
    }
}

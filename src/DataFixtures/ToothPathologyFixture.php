<?php

namespace App\DataFixtures;

use App\Entity\Odontogram;
use App\Entity\Pathology;
use App\Entity\Tooth;
use App\Entity\ToothPathology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ToothPathologyFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Explicit mapping to ensure varied pathology examples per odontogram
        $records = [
            [OdontogramFixture::ODONTOGRAM_JAN, ToothFixture::TOOTH_11, PathologyFixture::PATHOLOGY_CARIES, 1],
            [OdontogramFixture::ODONTOGRAM_FEB, ToothFixture::TOOTH_36, PathologyFixture::PATHOLOGY_SELLADO, 2],
            [OdontogramFixture::ODONTOGRAM_MAR, ToothFixture::TOOTH_21, PathologyFixture::PATHOLOGY_OBTURACION, 3],
            [OdontogramFixture::ODONTOGRAM_APR, ToothFixture::TOOTH_46, PathologyFixture::PATHOLOGY_AUSENCIA_NATURAL, 4],
            [OdontogramFixture::ODONTOGRAM_MAY, ToothFixture::TOOTH_14, PathologyFixture::PATHOLOGY_CARIES_RADIOGRAFIA, 1],
            [OdontogramFixture::ODONTOGRAM_JUN, ToothFixture::TOOTH_26, PathologyFixture::PATHOLOGY_OBTURACION, 2],
            [OdontogramFixture::ODONTOGRAM_JUL, ToothFixture::TOOTH_31, PathologyFixture::PATHOLOGY_SELLADO, 3],
            [OdontogramFixture::ODONTOGRAM_AUG, ToothFixture::TOOTH_41, PathologyFixture::PATHOLOGY_CARIES, 4],
            [OdontogramFixture::ODONTOGRAM_SEP, ToothFixture::TOOTH_18, PathologyFixture::PATHOLOGY_CARIES_RADIOGRAFIA, 1],
            [OdontogramFixture::ODONTOGRAM_OCT, ToothFixture::TOOTH_12, PathologyFixture::PATHOLOGY_AUSENCIA_NATURAL, 2],
            [OdontogramFixture::ODONTOGRAM_NOV, ToothFixture::TOOTH_46, PathologyFixture::PATHOLOGY_OBTURACION, 3],
            [OdontogramFixture::ODONTOGRAM_DEC, ToothFixture::TOOTH_52, PathologyFixture::PATHOLOGY_SELLADO, 4],
        ];

        foreach ($records as [$odontogramRef, $toothRef, $pathologyRef, $toothFace]) {
            $toothPathology = new ToothPathology();
            $toothPathology->setOdontogram($this->getReference($odontogramRef, Odontogram::class));
            $toothPathology->setTooth($this->getReference($toothRef, Tooth::class));
            $toothPathology->setPathology($this->getReference($pathologyRef, Pathology::class));
            $toothPathology->setToothFace($toothFace);

            $manager->persist($toothPathology);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OdontogramFixture::class,
            ToothFixture::class,
            PathologyFixture::class,
        ];
    }
}

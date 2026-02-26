<?php

namespace App\DataFixtures;

use App\Entity\Tooth;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ToothFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Dientes permanentes
        $permanentTeeth = [
            // Cuadrante superior derecho
            18, 17, 16, 15, 14, 13, 12, 11,
            // Cuadrante superior izquierdo
            21, 22, 23, 24, 25, 26, 27, 28,
            // Cuadrante inferior izquierdo
            31, 32, 33, 34, 35, 36, 37, 38,
            // Cuadrante inferior derecho
            41, 42, 43, 44, 45, 46, 47, 48,
        ];

        // Dientes temporales (deciduos)
        $temporaryTeeth = [
            // Cuadrante superior derecho
            55, 54, 53, 52, 51,
            // Cuadrante superior izquierdo
            61, 62, 63, 64, 65,
            // Cuadrante inferior izquierdo
            71, 72, 73, 74, 75,
            // Cuadrante inferior derecho
            81, 82, 83, 84, 85,
        ];

        $toothDescriptions = [
            // Permanentes
            11 => 'Incisivo central superior derecho',
            12 => 'Incisivo lateral superior derecho',
            13 => 'Canino superior derecho',
            14 => 'Primer premolar superior derecho',
            15 => 'Segundo premolar superior derecho',
            16 => 'Primer molar superior derecho',
            17 => 'Segundo molar superior derecho',
            18 => 'Tercer molar superior derecho (muela del juicio)',
            
            21 => 'Incisivo central superior izquierdo',
            22 => 'Incisivo lateral superior izquierdo',
            23 => 'Canino superior izquierdo',
            24 => 'Primer premolar superior izquierdo',
            25 => 'Segundo premolar superior izquierdo',
            26 => 'Primer molar superior izquierdo',
            27 => 'Segundo molar superior izquierdo',
            28 => 'Tercer molar superior izquierdo (muela del juicio)',
            
            31 => 'Incisivo central inferior izquierdo',
            32 => 'Incisivo lateral inferior izquierdo',
            33 => 'Canino inferior izquierdo',
            34 => 'Primer premolar inferior izquierdo',
            35 => 'Segundo premolar inferior izquierdo',
            36 => 'Primer molar inferior izquierdo',
            37 => 'Segundo molar inferior izquierdo',
            38 => 'Tercer molar inferior izquierdo (muela del juicio)',
            
            41 => 'Incisivo central inferior derecho',
            42 => 'Incisivo lateral inferior derecho',
            43 => 'Canino inferior derecho',
            44 => 'Primer premolar inferior derecho',
            45 => 'Segundo premolar inferior derecho',
            46 => 'Primer molar inferior derecho',
            47 => 'Segundo molar inferior derecho',
            48 => 'Tercer molar inferior derecho (muela del juicio)',
            
            // Temporales
            51 => 'Incisivo central temporal superior derecho',
            52 => 'Incisivo lateral temporal superior derecho',
            53 => 'Canino temporal superior derecho',
            54 => 'Primer molar temporal superior derecho',
            55 => 'Segundo molar temporal superior derecho',
            
            61 => 'Incisivo central temporal superior izquierdo',
            62 => 'Incisivo lateral temporal superior izquierdo',
            63 => 'Canino temporal superior izquierdo',
            64 => 'Primer molar temporal superior izquierdo',
            65 => 'Segundo molar temporal superior izquierdo',
            
            71 => 'Incisivo central temporal inferior izquierdo',
            72 => 'Incisivo lateral temporal inferior izquierdo',
            73 => 'Canino temporal inferior izquierdo',
            74 => 'Primer molar temporal inferior izquierdo',
            75 => 'Segundo molar temporal inferior izquierdo',
            
            81 => 'Incisivo central temporal inferior derecho',
            82 => 'Incisivo lateral temporal inferior derecho',
            83 => 'Canino temporal inferior derecho',
            84 => 'Primer molar temporal inferior derecho',
            85 => 'Segundo molar temporal inferior derecho',
        ];

        // Crear dientes permanentes
        foreach ($permanentTeeth as $number) {
            $tooth = new Tooth();
            $tooth->setToothNumber($number);
            $tooth->setDescription($toothDescriptions[$number] ?? "Diente $number");
            $manager->persist($tooth);
        }

        // Crear dientes temporales
        foreach ($temporaryTeeth as $number) {
            $tooth = new Tooth();
            $tooth->setToothNumber($number);
            $tooth->setDescription($toothDescriptions[$number] ?? "Diente temporal $number");
            $manager->persist($tooth);
        }

        $manager->flush();
    }
}

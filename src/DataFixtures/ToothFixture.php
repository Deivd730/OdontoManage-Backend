<?php

namespace App\DataFixtures;

use App\Entity\Tooth;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ToothFixture extends Fixture
{
    public const TOOTH_11 = 'tooth_11';
    public const TOOTH_12 = 'tooth_12';
    public const TOOTH_14 = 'tooth_14';
    public const TOOTH_18 = 'tooth_18';
    public const TOOTH_21 = 'tooth_21';
    public const TOOTH_26 = 'tooth_26';
    public const TOOTH_31 = 'tooth_31';
    public const TOOTH_36 = 'tooth_36';
    public const TOOTH_41 = 'tooth_41';
    public const TOOTH_46 = 'tooth_46';
    public const TOOTH_51 = 'tooth_51';
    public const TOOTH_52 = 'tooth_52';

    public function load(ObjectManager $manager): void
    {
        $teeth = [
            // Dientes permanentes - Cuadrante superior derecho
            [18, 'Tercer molar superior derecho (muela del juicio)'],
            [17, 'Segundo molar superior derecho'],
            [16, 'Primer molar superior derecho'],
            [15, 'Segundo premolar superior derecho'],
            [14, 'Primer premolar superior derecho'],
            [13, 'Canino superior derecho'],
            [12, 'Incisivo lateral superior derecho'],
            [11, 'Incisivo central superior derecho'],
            // Cuadrante superior izquierdo
            [21, 'Incisivo central superior izquierdo'],
            [22, 'Incisivo lateral superior izquierdo'],
            [23, 'Canino superior izquierdo'],
            [24, 'Primer premolar superior izquierdo'],
            [25, 'Segundo premolar superior izquierdo'],
            [26, 'Primer molar superior izquierdo'],
            [27, 'Segundo molar superior izquierdo'],
            [28, 'Tercer molar superior izquierdo (muela del juicio)'],
            // Cuadrante inferior izquierdo
            [31, 'Incisivo central inferior izquierdo'],
            [32, 'Incisivo lateral inferior izquierdo'],
            [33, 'Canino inferior izquierdo'],
            [34, 'Primer premolar inferior izquierdo'],
            [35, 'Segundo premolar inferior izquierdo'],
            [36, 'Primer molar inferior izquierdo'],
            [37, 'Segundo molar inferior izquierdo'],
            [38, 'Tercer molar inferior izquierdo (muela del juicio)'],
            // Cuadrante inferior derecho
            [41, 'Incisivo central inferior derecho'],
            [42, 'Incisivo lateral inferior derecho'],
            [43, 'Canino inferior derecho'],
            [44, 'Primer premolar inferior derecho'],
            [45, 'Segundo premolar inferior derecho'],
            [46, 'Primer molar inferior derecho'],
            [47, 'Segundo molar inferior derecho'],
            [48, 'Tercer molar inferior derecho (muela del juicio)'],
            // Dientes temporales - Cuadrante superior derecho
            [55, 'Segundo molar temporal superior derecho'],
            [54, 'Primer molar temporal superior derecho'],
            [53, 'Canino temporal superior derecho'],
            [52, 'Incisivo lateral temporal superior derecho'],
            [51, 'Incisivo central temporal superior derecho'],
            // Cuadrante superior izquierdo
            [61, 'Incisivo central temporal superior izquierdo'],
            [62, 'Incisivo lateral temporal superior izquierdo'],
            [63, 'Canino temporal superior izquierdo'],
            [64, 'Primer molar temporal superior izquierdo'],
            [65, 'Segundo molar temporal superior izquierdo'],
            // Cuadrante inferior izquierdo
            [71, 'Incisivo central temporal inferior izquierdo'],
            [72, 'Incisivo lateral temporal inferior izquierdo'],
            [73, 'Canino temporal inferior izquierdo'],
            [74, 'Primer molar temporal inferior izquierdo'],
            [75, 'Segundo molar temporal inferior izquierdo'],
            // Cuadrante inferior derecho
            [81, 'Incisivo central temporal inferior derecho'],
            [82, 'Incisivo lateral temporal inferior derecho'],
            [83, 'Canino temporal inferior derecho'],
            [84, 'Primer molar temporal inferior derecho'],
            [85, 'Segundo molar temporal inferior derecho'],
        ];

        foreach ($teeth as [$toothNumber, $description]) {
            $tooth = new Tooth();
            $tooth->setToothNumber($toothNumber);
            $tooth->setDescription($description);

            $manager->persist($tooth);

            $reference = match ($toothNumber) {
                11 => self::TOOTH_11,
                12 => self::TOOTH_12,
                14 => self::TOOTH_14,
                18 => self::TOOTH_18,
                21 => self::TOOTH_21,
                26 => self::TOOTH_26,
                31 => self::TOOTH_31,
                36 => self::TOOTH_36,
                41 => self::TOOTH_41,
                46 => self::TOOTH_46,
                51 => self::TOOTH_51,
                52 => self::TOOTH_52,
                default => null,
            };

            if ($reference !== null) {
                $this->addReference($reference, $tooth);
            }
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Box;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BoxFixture extends Fixture
{
    public const BOX_1 = 'box_1';
    public const BOX_2 = 'box_2';

    public function load(ObjectManager $manager): void
    {
        $boxes = [
            [
                'name' => 'Box 1',
                'status' => 'available',
                'reference' => self::BOX_1,
            ],
            [
                'name' => 'Box 2',
                'status' => 'available',
                'reference' => self::BOX_2,
            ],
        ];

        foreach ($boxes as $data) {
            $box = new Box();
            $box->setName($data['name']);
            $box->setStatus($data['status']);

            $manager->persist($box);
            $this->addReference($data['reference'], $box);
        }

        $manager->flush();
    }
}

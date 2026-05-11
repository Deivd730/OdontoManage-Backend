<?php

namespace App\DataFixtures;

use App\Entity\Dentist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DentistFixture extends Fixture implements DependentFixtureInterface
{
    public const DENTIST_ANA = 'dentist_ana';
    public const DENTIST_LUIS = 'dentist_luis';
    public const DENTIST_MARTA = 'dentist_marta';
    public const DENTIST_PEDRO = 'dentist_pedro';
    public const DENTIST_JAVIER = 'dentist_javier';
    public const DENTIST_JORGE = 'dentist_jorge';
    public const DENTIST_SOFIA = 'dentist_sofia';
    public const DENTIST_CARLOS = 'dentist_carlos';
    public const DENTIST_LAURA = 'dentist_laura';
    public const DENTIST_PEPE = 'dentist_pepe';

    public function load(ObjectManager $manager): void
    {
        $dentists = [
            [
                'email' => 'ana.garcia@clinic.local',
                'password' => '$2y$13$4S2gNqEVlx7k9JrLmP5Q.u0W3X6Y9Z2B5C8D1E4F7G0H3K6N9Q',
                'first_name' => 'Ana',
                'last_name' => 'Garcia',
                'available_days' => 'Mon',
                'phone' => '600111222',
                'reference' => self::DENTIST_ANA,
            ],
            [
                'email' => 'luis.martin@clinic.local',
                'password' => '$2y$13$4S2gNqEVlx7k9JrLmP5Q.u0W3X6Y9Z2B5C8D1E4F7G0H3K6N9Q',
                'first_name' => 'Luis',
                'last_name' => 'Martin',
                'available_days' => 'Tue',
                'phone' => '600333444',
                'reference' => self::DENTIST_LUIS,
            ],
            [
                'email' => 'marta.suarez@clinic.local',
                'password' => '$2y$13$4S2gNqEVlx7k9JrLmP5Q.u0W3X6Y9Z2B5C8D1E4F7G0H3K6N9Q',
                'first_name' => 'Marta',
                'last_name' => 'Suarez',
                'available_days' => 'Wed',
                'phone' => '600555666',
                'reference' => self::DENTIST_MARTA,
            ],
            [
                'email' => 'pedro.alvarez@clinic.local',
                'password' => '$2y$13$4S2gNqEVlx7k9JrLmP5Q.u0W3X6Y9Z2B5C8D1E4F7G0H3K6N9Q',
                'first_name' => 'Pedro',
                'last_name' => 'Alvarez',
                'available_days' => 'Thu',
                'phone' => '600777888',
                'reference' => self::DENTIST_PEDRO,
            ],
            [
                'email' => 'javier.gomez@clinic.local',
                'password' => '$2y$13$4S2gNqEVlx7k9JrLmP5Q.u0W3X6Y9Z2B5C8D1E4F7G0H3K6N9Q',
                'first_name' => 'Javier',
                'last_name' => 'Gomez',
                'available_days' => 'Fri',
                'phone' => '600999000',
                'reference' => self::DENTIST_JAVIER,
            ],
            [
                'email' => 'jorge.martinez@clinic.local',
                'password' => '$2y$13$4S2gNqEVlx7k9JrLmP5Q.u0W3X6Y9Z2B5C8D1E4F7G0H3K6N9Q',
                'first_name' => 'Jorge',
                'last_name' => 'Martinez',
                'available_days' => 'Mon',
                'phone' => '600000111',
                'reference' => self::DENTIST_JORGE,
            ],
            [
                'email' => 'sofia.lopez@clinic.local',
                'password' => '$2y$13$4S2gNqEVlx7k9JrLmP5Q.u0W3X6Y9Z2B5C8D1E4F7G0H3K6N9Q',
                'first_name' => 'Sofia',
                'last_name' => 'Lopez',
                'available_days' => 'Tue',
                'phone' => '600222333',
                'reference' => self::DENTIST_SOFIA,
            ],
            [
                'email' => 'carlos.perez@clinic.local',
                'password' => '$2y$13$4S2gNqEVlx7k9JrLmP5Q.u0W3X6Y9Z2B5C8D1E4F7G0H3K6N9Q',
                'first_name' => 'Carlos',
                'last_name' => 'Perez',
                'available_days' => 'Wed',
                'phone' => '600444555',
                'reference' => self::DENTIST_CARLOS,
            ],
            [
                'email' => 'laura.martinez@clinic.local',
                'password' => '$2y$13$4S2gNqEVlx7k9JrLmP5Q.u0W3X6Y9Z2B5C8D1E4F7G0H3K6N9Q',
                'first_name' => 'Laura',
                'last_name' => 'Martinez',
                'available_days' => 'Thu',
                'phone' => '600666777',
                'reference' => self::DENTIST_LAURA,
            ],
            [
                'email' => 'pepe.garcia@clinic.local',
                'password' => '$2y$13$4S2gNqEVlx7k9JrLmP5Q.u0W3X6Y9Z2B5C8D1E4F7G0H3K6N9Q',
                'first_name' => 'Pepe',
                'last_name' => 'Garcia',
                'available_days' => 'Fri',
                'phone' => '600888999',
                'reference' => self::DENTIST_PEPE,
            ],
        ];

        foreach ($dentists as $data) {
            $dentist = new Dentist();
            $dentist->setEmail($data['email']);
            $dentist->setPassword($data['password']);
            $dentist->setFirstName($data['first_name']);
            $dentist->setLastName($data['last_name']);
            $dentist->setAvailableDays($data['available_days']);
            $dentist->setPhone($data['phone']);
            $dentist->setRoles(['ROLE_DENTIST']);
            $dentist->setUpdatedAt(new \DateTimeImmutable('2026-02-24 09:00:00'));

            $manager->persist($dentist);
            $this->addReference($data['reference'], $dentist);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BoxFixture::class,
        ];
    }
}

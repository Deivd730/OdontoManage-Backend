<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\Box;
use App\Entity\Dentist;
use App\Entity\Document;
use App\Entity\Odontogram;
use App\Entity\Patient;
use App\Entity\Treatment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StressPatientFixture extends Fixture implements DependentFixtureInterface
{
    private const TOTAL_PATIENTS = 100;
    private const BATCH_SIZE = 20;

    private const FIRST_NAMES = [
        'Carlos', 'Elena', 'Javier', 'Lucia', 'Mario',
        'Sara', 'Andres', 'Nora', 'Diego', 'Ana',
    ];

    private const LAST_NAMES = [
        'Lopez', 'Perez', 'Santos', 'Ruiz', 'Vega',
        'Gil', 'Munoz', 'Pascual', 'Romero', 'Cortes',
    ];

    private const HEALTH_STATUS = [
        'Sense patologies',
        'Alergia lleu',
        'Hipertensio',
        'Asma controlada',
        'Bruxisme lleu',
        'Periodontitis',
        'Caries recurrent',
        'Sensibilitat dental',
    ];

    private const LIFESTYLE_HABITS = [
        'No fumador',
        'Sedentaria',
        'Exercici moderat',
        'Exercici regular',
        'Fumador',
        'Fumador ocasional',
    ];

    private const ALLERGIES = [
        'Cap',
        'Penicilina',
        'Ibuprofena',
        'Amoxicilina',
    ];

    private const CONSULTATION_REASONS = [
        'Control de caries',
        'Revision general',
        'Extraccion indicada',
        'Puente dental',
        'Corona sobre molar',
        'Control de sensibilidad',
        'Caries en revision',
        'Extraccion y seguimiento',
        'Revision de ortodoncia',
        'Corona provisional',
    ];

    private const ODONTOGRAM_TYPES = [
        Odontogram::TYPE_ADULT,
        Odontogram::TYPE_CHILD,
    ];

    public function load(ObjectManager $manager): void
    {
        $dentistRefs = [
            DentistFixture::DENTIST_ANA,
            DentistFixture::DENTIST_LUIS,
            DentistFixture::DENTIST_MARTA,
            DentistFixture::DENTIST_PEDRO,
            DentistFixture::DENTIST_JAVIER,
            DentistFixture::DENTIST_JORGE,
            DentistFixture::DENTIST_SOFIA,
            DentistFixture::DENTIST_CARLOS,
            DentistFixture::DENTIST_LAURA,
            DentistFixture::DENTIST_PEPE,
        ];

        $boxRefs = [
            BoxFixture::BOX_1,
            BoxFixture::BOX_2,
        ];

        $treatmentRefs = [
            TreatmentFixture::TREATMENT_OBTURACION,
            TreatmentFixture::TREATMENT_EXODONCIA,
            TreatmentFixture::TREATMENT_PUENTE,
            TreatmentFixture::TREATMENT_CORONA,
            TreatmentFixture::TREATMENT_ENDODONCIA,
        ];

        $startTime = microtime(true);

        for ($i = 1; $i <= self::TOTAL_PATIENTS; $i++) {
            $dentistRef = $dentistRefs[($i - 1) % count($dentistRefs)];
            $boxRef = $boxRefs[($i - 1) % count($boxRefs)];
            $treatmentRef = $treatmentRefs[($i - 1) % count($treatmentRefs)];

            $patient = new Patient();
            $patient->setFirstName(self::FIRST_NAMES[($i - 1) % count(self::FIRST_NAMES)]);
            $patient->setLastName(self::LAST_NAMES[($i - 1) % count(self::LAST_NAMES)] . ' ' . $i);
            $patient->setNationalId(sprintf('NID-%05d', $i));
            $patient->setBirthDate(new \DateTimeImmutable(sprintf('1980-%02d-%02d', (($i - 1) % 12) + 1, (($i - 1) % 28) + 1)));
            $patient->setSocialSecurityNumber(sprintf('SSN-%05d', $i));
            $patient->setPhone(sprintf('700%06d', $i));
            $patient->setEmail(sprintf('stress.patient.%03d@mail.local', $i));
            $patient->setAddress(sprintf('Carrer de Prova %d', $i));
            $patient->setBillingData(sprintf('NIF:%05d', $i));
            $patient->setHealthStatus(self::HEALTH_STATUS[($i - 1) % count(self::HEALTH_STATUS)]);
            $patient->setFamilyHistory('Sense antecedents');
            $patient->setLifestyleHabits(self::LIFESTYLE_HABITS[($i - 1) % count(self::LIFESTYLE_HABITS)]);
            $patient->setMedicationAllergies(self::ALLERGIES[($i - 1) % count(self::ALLERGIES)]);
            $patient->setMedicalTreatmentConsent(true);
            $patient->setAnesthesiaConsent(true);
            $patient->setHasInfectiousDiseases(false);
            $patient->setInfectiousDiseases(null);
            $patient->setRegistrationDate(new \DateTimeImmutable(sprintf('2026-05-%02d %02d:%02d:00', (($i - 1) % 28) + 1, 8 + (($i - 1) % 10), (($i * 7) % 60))));
            $patient->setProfileImageName(sprintf('patient_stress_%03d.png', $i));
            $patient->setUpdatedAt(new \DateTimeImmutable(sprintf('2026-05-%02d %02d:%02d:00', (($i - 1) % 28) + 1, 8 + (($i - 1) % 10), (($i * 7) % 60))));
            $patient->setDentist($this->getReference($dentistRef, Dentist::class));

            $manager->persist($patient);

            $appointment = new Appointment();
            $appointment->setVisitDate(new \DateTime(sprintf('2026-05-%02d %02d:00:00', (($i - 1) % 28) + 1, 9 + (($i - 1) % 8))));
            $appointment->setConsultationReason(self::CONSULTATION_REASONS[($i - 1) % count(self::CONSULTATION_REASONS)]);
            $appointment->setPatient($patient);
            $appointment->setDentist($this->getReference($dentistRef, Dentist::class));
            $appointment->setBox($this->getReference($boxRef, Box::class));
            $appointment->setTreatment($this->getReference($treatmentRef, Treatment::class));

            $manager->persist($appointment);

            $document = new Document();
            $document->setPatient($patient);
            $document->setType('Consentimiento');
            $document->setName(sprintf('Consentimiento paciente %03d', $i));
            $document->setFileUrl(sprintf('documents/stress/consentimiento_%03d.pdf', $i));
            $document->setCaptureDate(new \DateTime(sprintf('2026-05-%02d %02d:%02d:00', (($i - 1) % 28) + 1, 10 + (($i - 1) % 6), (($i * 11) % 60))));

            $manager->persist($document);

            $odontogram = new Odontogram();
            $odontogram->setPatient($patient);
            $odontogram->setAppointment($appointment);
            $odontogram->setType(self::ODONTOGRAM_TYPES[($i - 1) % count(self::ODONTOGRAM_TYPES)]);

            $manager->persist($odontogram);

            if ($i % self::BATCH_SIZE === 0) {
                $manager->flush();
                $manager->clear();
            }
        }

        $manager->flush();

        $elapsed = microtime(true) - $startTime;
        echo sprintf('Stress fixture completada: %d pacientes creados en %.2f segundos' . PHP_EOL, self::TOTAL_PATIENTS, $elapsed);
    }

    public function getDependencies(): array
    {
        return [
            DentistFixture::class,
            BoxFixture::class,
            TreatmentFixture::class,
        ];
    }
}

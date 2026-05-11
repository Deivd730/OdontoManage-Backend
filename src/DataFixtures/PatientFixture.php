<?php

namespace App\DataFixtures;

use App\Entity\Patient;
use App\Entity\Dentist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PatientFixture extends Fixture implements DependentFixtureInterface
{
    public const PATIENT_CARLOS = 'patient_carlos';
    public const PATIENT_ELENA = 'patient_elena';
    public const PATIENT_JAVIER = 'patient_javier';
    public const PATIENT_LUCIA = 'patient_lucia';
    public const PATIENT_MARIO = 'patient_mario';
    public const PATIENT_SARA = 'patient_sara';
    public const PATIENT_ANDRES = 'patient_andres';
    public const PATIENT_NORA = 'patient_nora';
    public const PATIENT_DIEGO = 'patient_diego';
    public const PATIENT_ANA = 'patient_ana';
    public const PATIENT_IVAN = 'patient_ivan';
    public const PATIENT_TOMAS = 'patient_tomas';

    public function load(ObjectManager $manager): void
    {
        $patients = [
            [
                'first_name' => 'Carlos',
                'last_name' => 'López',
                'national_id' => '12345678A',
                'birth_date' => '1990-03-15',
                'social_security_number' => 'SSN-001',
                'phone' => '700111222',
                'email' => 'carlos.lopez@mail.local',
                'address' => 'Carrer Major 1',
                'billing_data' => 'NIF:12345678A',
                'health_status' => 'Sense patologies',
                'family_history' => 'Sense antecedents',
                'lifestyle_habits' => 'No fumador',
                'medication_allergies' => 'Cap',
                'medical_treatment_consent' => true,
                'anesthesia_consent' => true,
                'has_infectious_diseases' => false,
                'infectious_diseases' => null,
                'registration_date' => '2026-02-24 10:00:00',
                'profile_image_name' => 'patient_carlos.png',
                'updated_at' => '2026-02-24 10:00:00',
                'dentist_ref' => DentistFixture::DENTIST_ANA,
                'reference' => self::PATIENT_CARLOS,
            ],
            [
                'first_name' => 'Elena',
                'last_name' => 'Pérez',
                'national_id' => '23456789B',
                'birth_date' => '1988-07-21',
                'social_security_number' => 'SSN-002',
                'phone' => '700333444',
                'email' => 'elena.perez@mail.local',
                'address' => 'Avinguda del Sol 5',
                'billing_data' => 'NIF:23456789B',
                'health_status' => 'Alergia lleu',
                'family_history' => 'Diabetis',
                'lifestyle_habits' => 'Sedentària',
                'medication_allergies' => 'Penicil·lina',
                'medical_treatment_consent' => true,
                'anesthesia_consent' => true,
                'has_infectious_diseases' => false,
                'infectious_diseases' => null,
                'registration_date' => '2026-02-24 10:05:00',
                'profile_image_name' => 'patient_elena.png',
                'updated_at' => '2026-02-24 10:05:00',
                'dentist_ref' => DentistFixture::DENTIST_LUIS,
                'reference' => self::PATIENT_ELENA,
            ],
            [
                'first_name' => 'Javier',
                'last_name' => 'Santos',
                'national_id' => '34567890C',
                'birth_date' => '1985-11-03',
                'social_security_number' => 'SSN-003',
                'phone' => '700555666',
                'email' => 'javier.santos@mail.local',
                'address' => 'Carrer Lluna 3',
                'billing_data' => 'NIF:34567890C',
                'health_status' => 'Hipertensió',
                'family_history' => 'Hipertensió',
                'lifestyle_habits' => 'Exercici moderat',
                'medication_allergies' => 'Cap',
                'medical_treatment_consent' => true,
                'anesthesia_consent' => true,
                'has_infectious_diseases' => true,
                'infectious_diseases' => 'hepatitis B',
                'registration_date' => '2026-02-24 10:10:00',
                'profile_image_name' => 'patient_javier.png',
                'updated_at' => '2026-02-24 10:10:00',
                'dentist_ref' => DentistFixture::DENTIST_MARTA,
                'reference' => self::PATIENT_JAVIER,
            ],
            [
                'first_name' => 'Llúcia',
                'last_name' => 'Ruiz',
                'national_id' => '45678901D',
                'birth_date' => '1992-01-28',
                'social_security_number' => 'SSN-004',
                'phone' => '700777888',
                'email' => 'lucia.ruiz@mail.local',
                'address' => 'Plaça Nord 7',
                'billing_data' => 'NIF:45678901D',
                'health_status' => 'Asma controlada',
                'family_history' => 'Asma',
                'lifestyle_habits' => 'No fumador',
                'medication_allergies' => 'Ibuprofè',
                'medical_treatment_consent' => true,
                'anesthesia_consent' => true,
                'has_infectious_diseases' => false,
                'infectious_diseases' => null,
                'registration_date' => '2026-02-24 10:15:00',
                'profile_image_name' => 'patient_lucia.png',
                'updated_at' => '2026-02-24 10:15:00',
                'dentist_ref' => DentistFixture::DENTIST_PEDRO,
                'reference' => self::PATIENT_LUCIA,
            ],
            [
                'first_name' => 'Mario',
                'last_name' => 'Vega',
                'national_id' => '56789012E',
                'birth_date' => '1995-09-12',
                'social_security_number' => 'SSN-005',
                'phone' => '700999000',
                'email' => 'mario.vega@mail.local',
                'address' => 'Carrer Sud 9',
                'billing_data' => 'NIF:56789012E',
                'health_status' => 'Sense patologies',
                'family_history' => 'Sense antecedents',
                'lifestyle_habits' => 'Fumador',
                'medication_allergies' => 'Cap',
                'medical_treatment_consent' => true,
                'anesthesia_consent' => true,
                'has_infectious_diseases' => false,
                'infectious_diseases' => null,
                'registration_date' => '2026-02-24 10:20:00',
                'profile_image_name' => 'patient_mario.png',
                'updated_at' => '2026-02-24 10:20:00',
                'dentist_ref' => DentistFixture::DENTIST_ANA,
                'reference' => self::PATIENT_MARIO,
            ],
            [
                'first_name' => 'Sara',
                'last_name' => 'Gil',
                'national_id' => '67890123F',
                'birth_date' => '1998-04-10',
                'social_security_number' => 'SSN-006',
                'phone' => '701111222',
                'email' => 'sara.gil@mail.local',
                'address' => 'Carrer del Prat 10',
                'billing_data' => 'NIF:67890123F',
                'health_status' => 'Bruxisme lleu',
                'family_history' => 'Sense antecedents',
                'lifestyle_habits' => 'No fumador',
                'medication_allergies' => 'Cap',
                'medical_treatment_consent' => true,
                'anesthesia_consent' => true,
                'has_infectious_diseases' => false,
                'infectious_diseases' => null,
                'registration_date' => '2026-02-24 10:25:00',
                'profile_image_name' => 'patient_sara.png',
                'updated_at' => '2026-02-24 10:25:00',
                'dentist_ref' => DentistFixture::DENTIST_LUIS,
                'reference' => self::PATIENT_SARA,
            ],
            [
                'first_name' => 'Andrés',
                'last_name' => 'Muñoz',
                'national_id' => '78901234G',
                'birth_date' => '1979-08-19',
                'social_security_number' => 'SSN-007',
                'phone' => '701333444',
                'email' => 'andres.munoz@mail.local',
                'address' => 'Avinguda Central 22',
                'billing_data' => 'NIF:78901234G',
                'health_status' => 'Periodontitis',
                'family_history' => 'Hipertensió',
                'lifestyle_habits' => 'Fumador ocasional',
                'medication_allergies' => 'Amoxicil·lina',
                'medical_treatment_consent' => true,
                'anesthesia_consent' => true,
                'has_infectious_diseases' => false,
                'infectious_diseases' => null,
                'registration_date' => '2026-02-24 10:30:00',
                'profile_image_name' => 'patient_andres.png',
                'updated_at' => '2026-02-24 10:30:00',
                'dentist_ref' => DentistFixture::DENTIST_MARTA,
                'reference' => self::PATIENT_ANDRES,
            ],
            [
                'first_name' => 'Nora',
                'last_name' => 'Pascual',
                'national_id' => '89012345H',
                'birth_date' => '2001-12-02',
                'social_security_number' => 'SSN-008',
                'phone' => '701555666',
                'email' => 'nora.pascual@mail.local',
                'address' => 'Carrer del Riu 4',
                'billing_data' => 'NIF:89012345H',
                'health_status' => 'Ortodòncia prèvia',
                'family_history' => 'Sense antecedents',
                'lifestyle_habits' => 'Exercici regular',
                'medication_allergies' => 'Cap',
                'medical_treatment_consent' => true,
                'anesthesia_consent' => true,
                'has_infectious_diseases' => false,
                'infectious_diseases' => null,
                'registration_date' => '2026-02-24 10:35:00',
                'profile_image_name' => 'patient_nora.png',
                'updated_at' => '2026-02-24 10:35:00',
                'dentist_ref' => DentistFixture::DENTIST_PEDRO,
                'reference' => self::PATIENT_NORA,
            ],
            [
                'first_name' => 'Diego',
                'last_name' => 'Romero',
                'national_id' => '90123456J',
                'birth_date' => '1983-05-14',
                'social_security_number' => 'SSN-009',
                'phone' => '701777888',
                'email' => 'diego.romero@mail.local',
                'address' => 'Plaça Nova 8',
                'billing_data' => 'NIF:90123456J',
                'health_status' => 'Càries recurrent',
                'family_history' => 'Diabetis',
                'lifestyle_habits' => 'No fumador',
                'medication_allergies' => 'Ibuprofè',
                'medical_treatment_consent' => true,
                'anesthesia_consent' => true,
                'has_infectious_diseases' => false,
                'infectious_diseases' => null,
                'registration_date' => '2026-02-24 10:40:00',
                'profile_image_name' => 'patient_diego.png',
                'updated_at' => '2026-02-24 10:40:00',
                'dentist_ref' => DentistFixture::DENTIST_JAVIER,
                'reference' => self::PATIENT_DIEGO,
            ],
            [
                'first_name' => 'Ana',
                'last_name' => 'Cortés',
                'national_id' => '01234567K',
                'birth_date' => '1993-10-26',
                'social_security_number' => 'SSN-010',
                'phone' => '701999000',
                'email' => 'ana.cortes@mail.local',
                'address' => 'Carrer del Jardí 15',
                'billing_data' => 'NIF:01234567K',
                'health_status' => 'Sensibilitat dental',
                'family_history' => 'Sense antecedents',
                'lifestyle_habits' => 'Fumador',
                'medication_allergies' => 'Cap',
                'medical_treatment_consent' => true,
                'anesthesia_consent' => true,
                'has_infectious_diseases' => false,
                'infectious_diseases' => null,
                'registration_date' => '2026-02-24 10:45:00',
                'profile_image_name' => 'patient_ana.png',
                'updated_at' => '2026-02-24 10:45:00',
                'dentist_ref' => DentistFixture::DENTIST_SOFIA,
                'reference' => self::PATIENT_ANA,
            ],
            [
                'first_name' => 'Iván',
                'last_name' => 'Morales',
                'national_id' => '11234567L',
                'birth_date' => '1987-02-11',
                'social_security_number' => 'SSN-011',
                'phone' => '702111222',
                'email' => 'ivan.morales@mail.local',
                'address' => 'Avinguda Costa 3',
                'billing_data' => 'NIF:11234567L',
                'health_status' => 'Endodòncia pendent',
                'family_history' => 'Hipertensió',
                'lifestyle_habits' => 'Exercici ocasional',
                'medication_allergies' => 'Cap',
                'medical_treatment_consent' => true,
                'anesthesia_consent' => true,
                'has_infectious_diseases' => false,
                'infectious_diseases' => null,
                'registration_date' => '2026-02-24 10:50:00',
                'profile_image_name' => 'patient_ivan.png',
                'updated_at' => '2026-02-24 10:50:00',
                'dentist_ref' => DentistFixture::DENTIST_CARLOS,
                'reference' => self::PATIENT_IVAN,
            ],
            [
                'first_name' => 'Tomàs',
                'last_name' => 'Herrera',
                'national_id' => '14234567P',
                'birth_date' => '2015-09-14',
                'social_security_number' => 'SSN-014',
                'phone' => '702777888',
                'email' => 'tomas.herrera@mail.local',
                'address' => 'Carrer Lliri 2',
                'billing_data' => 'NIF:14234567P',
                'health_status' => 'Revisió pediàtrica',
                'family_history' => 'Sense antecedents',
                'lifestyle_habits' => 'No fumador',
                'medication_allergies' => 'Cap',
                'medical_treatment_consent' => true,
                'anesthesia_consent' => true,
                'has_infectious_diseases' => false,
                'infectious_diseases' => null,
                'registration_date' => '2026-02-24 11:05:00',
                'profile_image_name' => 'patient_tomas.png',
                'updated_at' => '2026-02-24 11:05:00',
                'dentist_ref' => DentistFixture::DENTIST_SOFIA,
                'reference' => self::PATIENT_TOMAS,
            ],
        ];

        foreach ($patients as $data) {
            $patient = new Patient();
            $patient->setFirstName($data['first_name']);
            $patient->setLastName($data['last_name']);
            $patient->setNationalId($data['national_id']);
            $patient->setBirthDate(new \DateTimeImmutable($data['birth_date']));
            $patient->setSocialSecurityNumber($data['social_security_number']);
            $patient->setPhone($data['phone']);
            $patient->setEmail($data['email']);
            $patient->setAddress($data['address']);
            $patient->setBillingData($data['billing_data']);
            $patient->setHealthStatus($data['health_status']);
            $patient->setFamilyHistory($data['family_history']);
            $patient->setLifestyleHabits($data['lifestyle_habits']);
            $patient->setMedicationAllergies($data['medication_allergies']);
            $patient->setMedicalTreatmentConsent($data['medical_treatment_consent']);
            $patient->setAnesthesiaConsent($data['anesthesia_consent']);
            $patient->setHasInfectiousDiseases($data['has_infectious_diseases']);
            $patient->setInfectiousDiseases($data['infectious_diseases']);
            $patient->setRegistrationDate(new \DateTimeImmutable($data['registration_date']));
            $patient->setProfileImageName($data['profile_image_name']);
            $patient->setUpdatedAt(new \DateTimeImmutable($data['updated_at']));
            $patient->setDentist($this->getReference($data['dentist_ref'], Dentist::class));

            $manager->persist($patient);
            $this->addReference($data['reference'], $patient);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DentistFixture::class,
        ];
    }
}

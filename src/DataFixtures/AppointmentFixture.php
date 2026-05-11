<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\Box;
use App\Entity\Dentist;
use App\Entity\Patient;
use App\Entity\Treatment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppointmentFixture extends Fixture implements DependentFixtureInterface
{
    public const APPOINTMENT_JAN = 'appointment_jan';
    public const APPOINTMENT_FEB = 'appointment_feb';
    public const APPOINTMENT_MAR = 'appointment_mar';
    public const APPOINTMENT_APR = 'appointment_apr';
    public const APPOINTMENT_MAY = 'appointment_may';
    public const APPOINTMENT_JUN = 'appointment_jun';
    public const APPOINTMENT_JUL = 'appointment_jul';
    public const APPOINTMENT_AUG = 'appointment_aug';
    public const APPOINTMENT_SEP = 'appointment_sep';
    public const APPOINTMENT_OCT = 'appointment_oct';
    public const APPOINTMENT_NOV = 'appointment_nov';
    public const APPOINTMENT_DEC = 'appointment_dec';

    public function load(ObjectManager $manager): void
    {
        $appointments = [
            [
                'visit_date' => '2026-01-01 09:00:00',
                'consultation_reason' => 'Control de caries',
                'patient_ref' => PatientFixture::PATIENT_CARLOS,
                'dentist_ref' => DentistFixture::DENTIST_PEDRO,
                'box_ref' => BoxFixture::BOX_1,
                'treatment_ref' => TreatmentFixture::TREATMENT_OBTURACION,
                'reference' => self::APPOINTMENT_JAN,
            ],
            [
                'visit_date' => '2026-02-02 09:30:00',
                'consultation_reason' => 'Revisión general',
                'patient_ref' => PatientFixture::PATIENT_ELENA,
                'dentist_ref' => DentistFixture::DENTIST_ANA,
                'box_ref' => BoxFixture::BOX_2,
                'treatment_ref' => TreatmentFixture::TREATMENT_ENDODONCIA,
                'reference' => self::APPOINTMENT_FEB,
            ],
            [
                'visit_date' => '2026-03-03 10:00:00',
                'consultation_reason' => 'Extracción indicada',
                'patient_ref' => PatientFixture::PATIENT_JAVIER,
                'dentist_ref' => DentistFixture::DENTIST_LUIS,
                'box_ref' => BoxFixture::BOX_1,
                'treatment_ref' => TreatmentFixture::TREATMENT_EXODONCIA,
                'reference' => self::APPOINTMENT_MAR,
            ],
            [
                'visit_date' => '2026-04-08 09:15:00',
                'consultation_reason' => 'Puente dental',
                'patient_ref' => PatientFixture::PATIENT_LUCIA,
                'dentist_ref' => DentistFixture::DENTIST_MARTA,
                'box_ref' => BoxFixture::BOX_2,
                'treatment_ref' => TreatmentFixture::TREATMENT_PUENTE,
                'reference' => self::APPOINTMENT_APR,
            ],
            [
                'visit_date' => '2026-05-01 11:00:00',
                'consultation_reason' => 'Corona sobre molar',
                'patient_ref' => PatientFixture::PATIENT_MARIO,
                'dentist_ref' => DentistFixture::DENTIST_JAVIER,
                'box_ref' => BoxFixture::BOX_1,
                'treatment_ref' => TreatmentFixture::TREATMENT_CORONA,
                'reference' => self::APPOINTMENT_MAY,
            ],
            [
                'visit_date' => '2026-06-01 09:45:00',
                'consultation_reason' => 'Control de sensibilidad',
                'patient_ref' => PatientFixture::PATIENT_SARA,
                'dentist_ref' => DentistFixture::DENTIST_JORGE,
                'box_ref' => BoxFixture::BOX_2,
                'treatment_ref' => TreatmentFixture::TREATMENT_OBTURACION,
                'reference' => self::APPOINTMENT_JUN,
            ],
            [
                'visit_date' => '2026-07-01 10:30:00',
                'consultation_reason' => 'Caries en revisión',
                'patient_ref' => PatientFixture::PATIENT_ANDRES,
                'dentist_ref' => DentistFixture::DENTIST_CARLOS,
                'box_ref' => BoxFixture::BOX_1,
                'treatment_ref' => TreatmentFixture::TREATMENT_ENDODONCIA,
                'reference' => self::APPOINTMENT_JUL,
            ],
            [
                'visit_date' => '2026-08-06 09:00:00',
                'consultation_reason' => 'Extracción y seguimiento',
                'patient_ref' => PatientFixture::PATIENT_NORA,
                'dentist_ref' => DentistFixture::DENTIST_LAURA,
                'box_ref' => BoxFixture::BOX_2,
                'treatment_ref' => TreatmentFixture::TREATMENT_EXODONCIA,
                'reference' => self::APPOINTMENT_AUG,
            ],
            [
                'visit_date' => '2026-09-01 11:15:00',
                'consultation_reason' => 'Revisión de ortodoncia',
                'patient_ref' => PatientFixture::PATIENT_DIEGO,
                'dentist_ref' => DentistFixture::DENTIST_SOFIA,
                'box_ref' => BoxFixture::BOX_1,
                'treatment_ref' => TreatmentFixture::TREATMENT_PUENTE,
                'reference' => self::APPOINTMENT_SEP,
            ],
            [
                'visit_date' => '2026-10-01 09:30:00',
                'consultation_reason' => 'Corona provisional',
                'patient_ref' => PatientFixture::PATIENT_ANA,
                'dentist_ref' => DentistFixture::DENTIST_PEDRO,
                'box_ref' => BoxFixture::BOX_2,
                'treatment_ref' => TreatmentFixture::TREATMENT_CORONA,
                'reference' => self::APPOINTMENT_OCT,
            ],
            [
                'visit_date' => '2026-11-06 10:15:00',
                'consultation_reason' => 'Endodoncia programada',
                'patient_ref' => PatientFixture::PATIENT_IVAN,
                'dentist_ref' => DentistFixture::DENTIST_PEPE,
                'box_ref' => BoxFixture::BOX_1,
                'treatment_ref' => TreatmentFixture::TREATMENT_ENDODONCIA,
                'reference' => self::APPOINTMENT_NOV,
            ],
            [
                'visit_date' => '2026-12-02 09:00:00',
                'consultation_reason' => 'Revisión final del año',
                'patient_ref' => PatientFixture::PATIENT_TOMAS,
                'dentist_ref' => DentistFixture::DENTIST_MARTA,
                'box_ref' => BoxFixture::BOX_2,
                'treatment_ref' => TreatmentFixture::TREATMENT_OBTURACION,
                'reference' => self::APPOINTMENT_DEC,
            ],
        ];

        foreach ($appointments as $data) {
            $appointment = new Appointment();
            $appointment->setVisitDate(new \DateTime($data['visit_date']));
            $appointment->setConsultationReason($data['consultation_reason']);
            $appointment->setPatient($this->getReference($data['patient_ref'], Patient::class));
            $appointment->setDentist($this->getReference($data['dentist_ref'], Dentist::class));
            $appointment->setBox($this->getReference($data['box_ref'], Box::class));
            $appointment->setTreatment($this->getReference($data['treatment_ref'], Treatment::class));

            $manager->persist($appointment);
            $this->addReference($data['reference'], $appointment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PatientFixture::class,
            DentistFixture::class,
            BoxFixture::class,
            TreatmentFixture::class,
        ];
    }
}

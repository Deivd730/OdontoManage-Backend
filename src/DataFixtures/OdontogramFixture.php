<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\Odontogram;
use App\Entity\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OdontogramFixture extends Fixture implements DependentFixtureInterface
{
    public const ODONTOGRAM_JAN = 'odontogram_jan';
    public const ODONTOGRAM_FEB = 'odontogram_feb';
    public const ODONTOGRAM_MAR = 'odontogram_mar';
    public const ODONTOGRAM_APR = 'odontogram_apr';
    public const ODONTOGRAM_MAY = 'odontogram_may';
    public const ODONTOGRAM_JUN = 'odontogram_jun';
    public const ODONTOGRAM_JUL = 'odontogram_jul';
    public const ODONTOGRAM_AUG = 'odontogram_aug';
    public const ODONTOGRAM_SEP = 'odontogram_sep';
    public const ODONTOGRAM_OCT = 'odontogram_oct';
    public const ODONTOGRAM_NOV = 'odontogram_nov';
    public const ODONTOGRAM_DEC = 'odontogram_dec';

    public function load(ObjectManager $manager): void
    {
        $odontograms = [
            [PatientFixture::PATIENT_CARLOS, AppointmentFixture::APPOINTMENT_JAN, Odontogram::TYPE_ADULT, self::ODONTOGRAM_JAN],
            [PatientFixture::PATIENT_ELENA, AppointmentFixture::APPOINTMENT_FEB, Odontogram::TYPE_ADULT, self::ODONTOGRAM_FEB],
            [PatientFixture::PATIENT_JAVIER, AppointmentFixture::APPOINTMENT_MAR, Odontogram::TYPE_ADULT, self::ODONTOGRAM_MAR],
            [PatientFixture::PATIENT_LUCIA, AppointmentFixture::APPOINTMENT_APR, Odontogram::TYPE_ADULT, self::ODONTOGRAM_APR],
            [PatientFixture::PATIENT_MARIO, AppointmentFixture::APPOINTMENT_MAY, Odontogram::TYPE_ADULT, self::ODONTOGRAM_MAY],
            [PatientFixture::PATIENT_SARA, AppointmentFixture::APPOINTMENT_JUN, Odontogram::TYPE_ADULT, self::ODONTOGRAM_JUN],
            [PatientFixture::PATIENT_ANDRES, AppointmentFixture::APPOINTMENT_JUL, Odontogram::TYPE_ADULT, self::ODONTOGRAM_JUL],
            [PatientFixture::PATIENT_NORA, AppointmentFixture::APPOINTMENT_AUG, Odontogram::TYPE_ADULT, self::ODONTOGRAM_AUG],
            [PatientFixture::PATIENT_DIEGO, AppointmentFixture::APPOINTMENT_SEP, Odontogram::TYPE_ADULT, self::ODONTOGRAM_SEP],
            [PatientFixture::PATIENT_ANA, AppointmentFixture::APPOINTMENT_OCT, Odontogram::TYPE_ADULT, self::ODONTOGRAM_OCT],
            [PatientFixture::PATIENT_IVAN, AppointmentFixture::APPOINTMENT_NOV, Odontogram::TYPE_ADULT, self::ODONTOGRAM_NOV],
            [PatientFixture::PATIENT_TOMAS, AppointmentFixture::APPOINTMENT_DEC, Odontogram::TYPE_CHILD, self::ODONTOGRAM_DEC],
        ];

        foreach ($odontograms as [$patientRef, $appointmentRef, $type, $reference]) {
            $odontogram = new Odontogram();
            $odontogram->setPatient($this->getReference($patientRef, Patient::class));
            $odontogram->setAppointment($this->getReference($appointmentRef, Appointment::class));
            $odontogram->setType($type);

            $manager->persist($odontogram);
            $this->addReference($reference, $odontogram);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PatientFixture::class,
            AppointmentFixture::class,
        ];
    }
}

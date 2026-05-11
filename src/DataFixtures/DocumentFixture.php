<?php

namespace App\DataFixtures;

use App\Entity\Document;
use App\Entity\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DocumentFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $documents = [
            // Documentos de Tratamiento Médico
            [PatientFixture::PATIENT_CARLOS, 'Consentimiento', 'Consentimiento de tratamiento médico', 'consentimiento_carlos.pdf', '2026-02-24', 1],
            [PatientFixture::PATIENT_ELENA, 'Consentimiento', 'Consentimiento de tratamiento médico', 'consentimiento_elena.pdf', '2026-02-24', 2],
            [PatientFixture::PATIENT_JAVIER, 'Consentimiento', 'Consentimiento de tratamiento médico', 'consentimiento_javier.pdf', '2026-02-24', 3],
            [PatientFixture::PATIENT_LUCIA, 'Consentimiento', 'Consentimiento de tratamiento médico', 'consentimiento_lucia.pdf', '2026-02-24', 4],
            [PatientFixture::PATIENT_MARIO, 'Consentimiento', 'Consentimiento de tratamiento médico', 'consentimiento_mario.pdf', '2026-02-24', 5],
            [PatientFixture::PATIENT_SARA, 'Consentimiento', 'Consentimiento de tratamiento médico', 'consentimiento_sara.pdf', '2026-02-24', 1],
            [PatientFixture::PATIENT_ANDRES, 'Consentimiento', 'Consentimiento de tratamiento médico', 'consentimiento_andres.pdf', '2026-02-24', 2],
            [PatientFixture::PATIENT_NORA, 'Consentimiento', 'Consentimiento de tratamiento médico', 'consentimiento_nora.pdf', '2026-02-24', 3],
            [PatientFixture::PATIENT_DIEGO, 'Consentimiento', 'Consentimiento de tratamiento médico', 'consentimiento_diego.pdf', '2026-02-24', 4],
            [PatientFixture::PATIENT_ANA, 'Consentimiento', 'Consentimiento de tratamiento médico', 'consentimiento_ana.pdf', '2026-02-24', 5],
            [PatientFixture::PATIENT_IVAN, 'Consentimiento', 'Consentimiento de tratamiento médico', 'consentimiento_ivan.pdf', '2026-02-24', 1],
            [PatientFixture::PATIENT_TOMAS, 'Consentimiento', 'Consentimiento de tratamiento médico', 'consentimiento_tomas.pdf', '2026-02-24', 3],
            // Documentos de Anestesia Local
            [PatientFixture::PATIENT_CARLOS, 'Consentimiento', 'Consentimiento informado para anestesia local', 'consentimiento_anestesia_carlos.pdf', '2026-02-24', 1],
            [PatientFixture::PATIENT_ELENA, 'Consentimiento', 'Consentimiento informado para anestesia local', 'consentimiento_anestesia_elena.pdf', '2026-02-24', 2],
            [PatientFixture::PATIENT_JAVIER, 'Consentimiento', 'Consentimiento informado para anestesia local', 'consentimiento_anestesia_javier.pdf', '2026-02-24', 3],
            [PatientFixture::PATIENT_LUCIA, 'Consentimiento', 'Consentimiento informado para anestesia local', 'consentimiento_anestesia_lucia.pdf', '2026-02-24', 4],
            [PatientFixture::PATIENT_MARIO, 'Consentimiento', 'Consentimiento informado para anestesia local', 'consentimiento_anestesia_mario.pdf', '2026-02-24', 5],
            [PatientFixture::PATIENT_SARA, 'Consentimiento', 'Consentimiento informado para anestesia local', 'consentimiento_anestesia_sara.pdf', '2026-02-24', 1],
            [PatientFixture::PATIENT_ANDRES, 'Consentimiento', 'Consentimiento informado para anestesia local', 'consentimiento_anestesia_andres.pdf', '2026-02-24', 2],
            [PatientFixture::PATIENT_NORA, 'Consentimiento', 'Consentimiento informado para anestesia local', 'consentimiento_anestesia_nora.pdf', '2026-02-24', 3],
            [PatientFixture::PATIENT_DIEGO, 'Consentimiento', 'Consentimiento informado para anestesia local', 'consentimiento_anestesia_diego.pdf', '2026-02-24', 4],
            [PatientFixture::PATIENT_ANA, 'Consentimiento', 'Consentimiento informado para anestesia local', 'consentimiento_anestesia_ana.pdf', '2026-02-24', 5],
            [PatientFixture::PATIENT_IVAN, 'Consentimiento', 'Consentimiento informado para anestesia local', 'consentimiento_anestesia_ivan.pdf', '2026-02-24', 1],
            [PatientFixture::PATIENT_TOMAS, 'Consentimiento', 'Consentimiento informado para anestesia local', 'consentimiento_anestesia_tomas.pdf', '2026-02-24', 3],
        ];

        foreach ($documents as [$patientRef, $type, $name, $fileUrl, $captureDate, $index]) {
            $document = new Document();
            $document->setPatient($this->getReference($patientRef, Patient::class));
            $document->setType($type);
            $document->setName($name);
            $document->setFileUrl($fileUrl);
            $document->setCaptureDate(new \DateTime($captureDate));

            $manager->persist($document);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PatientFixture::class,
        ];
    }
}

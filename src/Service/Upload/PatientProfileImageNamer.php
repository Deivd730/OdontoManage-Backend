<?php

namespace App\Service\Upload;

use App\Entity\Patient;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class PatientProfileImageNamer implements NamerInterface
{
    public function name(object $object, PropertyMapping $mapping): string
    {
        $file = $mapping->getFile($object);

        $extension = $file->guessExtension() ?: $file->getClientOriginalExtension();
        $extension = is_string($extension) && $extension !== '' ? strtolower($extension) : 'bin';

        $patientKey = 'patient';
        if ($object instanceof Patient) {
            $patientId = $object->getId();
            if (is_int($patientId)) {
                $patientKey = 'patient-' . $patientId;
            } else {
                $nationalId = $object->getNationalId();
                if (is_string($nationalId) && trim($nationalId) !== '') {
                    $normalizedNationalId = preg_replace('/[^a-zA-Z0-9_-]/', '-', $nationalId) ?? 'patient';
                    $patientKey = 'patient-' . trim($normalizedNationalId, '-');
                }
            }
        }

        $timestamp = (new \DateTimeImmutable())->format('YmdHis');
        $uniqueSuffix = bin2hex(random_bytes(4));

        return sprintf('%s-%s-%s.%s', $patientKey, $timestamp, $uniqueSuffix, $extension);
    }
}

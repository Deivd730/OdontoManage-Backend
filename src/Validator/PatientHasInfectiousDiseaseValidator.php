<?php

namespace App\Validator;

use App\Entity\Appointment;
use App\Service\Scheduling\ClinicSchedulePolicy;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PatientHasInfectiousDiseaseValidator extends ConstraintValidator
{
    public function __construct(
        private ClinicSchedulePolicy $clinicSchedulePolicy,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PatientHasInfectiousDisease) {
            throw new UnexpectedTypeException($constraint, PatientHasInfectiousDisease::class);
        }

        if (!$value instanceof Appointment) {
            throw new UnexpectedTypeException($value, Appointment::class);
        }

        $appointment = $value;
        $patient = $appointment->getPatient();
        $visitDate = $appointment->getVisitDate();
        $treatment = $appointment->getTreatment();

        if (!$patient || !$visitDate || !$treatment) {
            return;
        }

        if (!$patient->getHasInfectiousDiseases()) {
            return;
        }

        $durationMinutes = $treatment->getDurationMinutes();
        if (!$durationMinutes || $durationMinutes <= 0) {
            return;
        }

        $lastPossibleStart = $this->clinicSchedulePolicy->getLastPossibleStartForTreatment($visitDate, $durationMinutes);

        $selectedSlot = \DateTime::createFromInterface($visitDate)->setTime(
            (int) $visitDate->format('H'),
            (int) $visitDate->format('i'),
            0
        );

        if ($selectedSlot->format('Y-m-d H:i') !== $lastPossibleStart->format('Y-m-d H:i')) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ time }}', $lastPossibleStart->format('H:i'))
                ->atPath('visitDate')
                ->addViolation();
        }
    }
}

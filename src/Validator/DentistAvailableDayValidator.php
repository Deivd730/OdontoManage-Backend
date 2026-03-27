<?php

namespace App\Validator;

use App\Entity\Appointment;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DentistAvailableDayValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof DentistAvailableDay) {
            throw new UnexpectedTypeException($constraint, DentistAvailableDay::class);
        }

        if (!$value instanceof Appointment) {
            throw new UnexpectedTypeException($value, Appointment::class);
        }

        $appointment = $value;
        $dentist = $appointment->getDentist();
        $visitDate = $appointment->getVisitDate();

        if (!$dentist || !$visitDate) {
            return;
        }

        $availableDays = $dentist->getAvailableDays();
        
        if (!$availableDays) {
            // If no available days are set, allow any day
            return;
        }

        // Use ISO-8601 day of week number (1=Mon ... 7=Sun)
        $dayOfWeek = (int) $visitDate->format('N');

        if ($dayOfWeek !== $availableDays) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ day }}', (string) $dayOfWeek)
            ->setParameter('{{ availableDays }}', (string) $availableDays)
                ->atPath('dentist')
                ->addViolation();
        }
    }
}

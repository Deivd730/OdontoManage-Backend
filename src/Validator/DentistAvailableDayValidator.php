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

        // Get day of week (Mon, Tue, Wed, Thu, Fri, Sat, Sun)
        $dayOfWeek = $visitDate->format('D');
        
        // Parse available days (format: "Mon,Wed,Fri")
        $availableDaysArray = array_map('trim', explode(',', $availableDays));

        if (!in_array($dayOfWeek, $availableDaysArray, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ day }}', $dayOfWeek)
                ->setParameter('{{ availableDays }}', $availableDays)
                ->atPath('dentist')
                ->addViolation();
        }
    }
}

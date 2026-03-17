<?php

namespace App\Validator;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueBoxTimeSlotValidator extends ConstraintValidator
{
    public function __construct(
        private AppointmentRepository $appointmentRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueBoxTimeSlot) {
            throw new UnexpectedTypeException($constraint, UniqueBoxTimeSlot::class);
        }

        if (!$value instanceof Appointment) {
            throw new UnexpectedTypeException($value, Appointment::class);
        }

        $appointment = $value;
        $box = $appointment->getBox();
        $visitDate = $appointment->getVisitDate();
        $treatment = $appointment->getTreatment();

        if (!$box || !$visitDate || !$treatment) {
            return;
        }

        $durationMinutes = $treatment->getDurationMinutes();
        if (!$durationMinutes) {
            return; // Cannot validate without duration
        }

        // Buffer minutes required after each appointment
        $bufferMinutes = 5;

        // Start and end times for this appointment
        $startTime = \DateTime::createFromInterface($visitDate);
        $endTime = \DateTime::createFromInterface($visitDate);
        $endTime->modify('+' . $durationMinutes . ' minutes');
        $endWithBuffer = (clone $endTime)->modify('+' . $bufferMinutes . ' minutes');

        // Enforce clinic working hours: 09:00 - 17:00 (appointment end + buffer must be <= 17:00)
        $dayStart = (clone $startTime)->setTime(9, 0, 0);
        $dayEndAllowed = (clone $startTime)->setTime(17, 0, 0);

        if ($startTime < $dayStart || $endWithBuffer > $dayEndAllowed) {
            $this->context->buildViolation('Appointments must be scheduled between 09:00 and 17:00 and include a 5-minute buffer after the appointment.')
                ->atPath('visitDate')
                ->addViolation();
            return;
        }

        // Load same-day appointments for the same box and check overlaps in PHP taking buffer into account
        $dayStart = \DateTime::createFromInterface($visitDate);
        $dayStart = $dayStart->setTime(0, 0, 0);
        $dayEnd = \DateTime::createFromInterface($visitDate);
        $dayEnd = $dayEnd->setTime(23, 59, 59);

        $qb = $this->appointmentRepository->createQueryBuilder('a')
            ->innerJoin('a.treatment', 't')
            ->where('a.box = :box')
            ->andWhere('a.visitDate >= :dayStart')
            ->andWhere('a.visitDate <= :dayEnd')
            ->setParameter('box', $box)
            ->setParameter('dayStart', $dayStart)
            ->setParameter('dayEnd', $dayEnd);

        if ($appointment->getId()) {
            $qb->andWhere('a.id != :currentId')
                ->setParameter('currentId', $appointment->getId());
        }

        $otherAppointments = $qb->getQuery()->getResult();

        foreach ($otherAppointments as $other) {
            $otherTreatment = $other->getTreatment();
            if (!$otherTreatment) {
                continue;
            }

            $otherStart = \DateTime::createFromInterface($other->getVisitDate());
            $otherEnd = (clone $otherStart)->modify('+' . $otherTreatment->getDurationMinutes() . ' minutes');
            $otherEndWithBuffer = (clone $otherEnd)->modify('+' . $bufferMinutes . ' minutes');

            // Overlap if otherStart < thisEndWithBuffer AND otherEndWithBuffer > thisStart
            if ($otherStart < $endWithBuffer && $otherEndWithBuffer > $startTime) {
                $this->context->buildViolation($constraint->message)
                    ->atPath('box')
                    ->addViolation();
                return;
            }
        }
    }
}

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

        // Calculate end time based on treatment duration
        $startTime = \DateTime::createFromInterface($visitDate);
        $endTime = \DateTime::createFromInterface($visitDate);
        $endTime->modify('+' . $treatment->getDurationMinutes() . ' minutes');

        // Find overlapping appointments for the same box
        $qb = $this->appointmentRepository->createQueryBuilder('a')
            ->innerJoin('a.treatment', 't')
            ->where('a.box = :box')
            ->andWhere('a.visitDate < :endTime')
            ->andWhere('DATE_ADD(a.visitDate, t.durationMinutes, \'MINUTE\') > :startTime')
            ->setParameter('box', $box)
            ->setParameter('startTime', $startTime)
            ->setParameter('endTime', $endTime);

        // Exclude current appointment if updating
        if ($appointment->getId()) {
            $qb->andWhere('a.id != :currentId')
                ->setParameter('currentId', $appointment->getId());
        }

        $conflictingAppointments = $qb->getQuery()->getResult();

        if (count($conflictingAppointments) > 0) {
            $this->context->buildViolation($constraint->message)
                ->atPath('box')
                ->addViolation();
        }
    }
}

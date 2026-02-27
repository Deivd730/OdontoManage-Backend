<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueBoxTimeSlot extends Constraint
{
    public string $message = 'This box is already booked for the selected time slot.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}

<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class DentistAvailableDay extends Constraint
{
    public string $message = 'The dentist does not work on {{ day }}. Available days: {{ availableDays }}.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}

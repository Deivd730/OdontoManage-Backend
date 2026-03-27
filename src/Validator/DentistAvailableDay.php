<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class DentistAvailableDay extends Constraint
{
    public string $message = 'The dentist does not work on day {{ day }} (ISO 1-7). Available day: {{ availableDays }}.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}

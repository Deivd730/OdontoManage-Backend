<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PatientHasInfectiousDisease extends Constraint
{
	public string $message = 'This patient has an infectious disease and can only be scheduled in the last available slot of the day ({{ time }}).';

	public function getTargets(): string
	{
		return self::CLASS_CONSTRAINT;
	}
}

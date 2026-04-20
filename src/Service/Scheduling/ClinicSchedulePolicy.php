<?php

namespace App\Service\Scheduling;

class ClinicSchedulePolicy
{
    public function __construct(
        private readonly string $workingDayStart,
        private readonly string $workingDayEnd,
        private readonly int $bufferMinutes,
    ) {
    }

    public function getBufferMinutes(): int
    {
        return $this->bufferMinutes;
    }

    public function getWorkingDayStartFor(\DateTimeInterface $date): \DateTimeImmutable
    {
        [$hour, $minute] = $this->parseHourMinute($this->workingDayStart);

        return \DateTimeImmutable::createFromInterface($date)->setTime($hour, $minute, 0);
    }

    public function getWorkingDayEndFor(\DateTimeInterface $date): \DateTimeImmutable
    {
        [$hour, $minute] = $this->parseHourMinute($this->workingDayEnd);

        return \DateTimeImmutable::createFromInterface($date)->setTime($hour, $minute, 0);
    }

    public function getLastPossibleStartForTreatment(\DateTimeInterface $date, int $durationMinutes): \DateTimeImmutable
    {
        return $this->getWorkingDayEndFor($date)->modify('-' . ($this->bufferMinutes + $durationMinutes) . ' minutes');
    }

    public function isWithinWorkingWindow(\DateTimeInterface $visitDate, int $durationMinutes): bool
    {
        if ($durationMinutes <= 0) {
            return false;
        }

        $startTime = \DateTimeImmutable::createFromInterface($visitDate);
        $endWithBuffer = $startTime
            ->modify('+' . $durationMinutes . ' minutes')
            ->modify('+' . $this->bufferMinutes . ' minutes');

        return $startTime >= $this->getWorkingDayStartFor($visitDate)
            && $endWithBuffer <= $this->getWorkingDayEndFor($visitDate);
    }

    private function parseHourMinute(string $hhmm): array
    {
        $parts = explode(':', $hhmm);

        return [(int) ($parts[0] ?? 0), (int) ($parts[1] ?? 0)];
    }
}

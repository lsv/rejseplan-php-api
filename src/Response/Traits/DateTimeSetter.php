<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Traits;

use DateTime;
use DateTimeZone;

trait DateTimeSetter
{
    protected function timeSetter(string $time, string $property): self
    {
        if (!$this->{$property} instanceof DateTime) {
            $this->{$property} = new DateTime('now',
                new DateTimeZone('Europe/Copenhagen'));
        }

        [$hour, $min] = explode(':', $time);
        $this->{$property}->setTime((int) $hour, (int) $min);

        return $this;
    }

    protected function dateSetter(string $date, string $property): self
    {
        if (!$this->{$property} instanceof DateTime) {
            $this->{$property} = new DateTime('now',
                new DateTimeZone('Europe/Copenhagen'));
        }

        [$day, $month, $year] = explode('.', $date);
        if (2 === strlen($year)) {
            $year = 20 .$year;
        }

        $this->{$property}->setDate((int) $year, (int) $month, (int) $day);

        return $this;
    }
}

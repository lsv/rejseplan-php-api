<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Traits;

use DateInterval;
use DateTime;
use DateTimeInterface;

trait ScheduledArrivalTrait
{
    /**
     * Scheduled arrival for this stop.
     */
    public ?DateTimeInterface $scheduledArrival = null;

    /**
     * Scheduled arrival for this stop.
     */
    public ?DateTimeInterface $realtimeArrival = null;

    public function setArrTime(string $time): self
    {
        $this->timeSetter($time, 'scheduledArrival');

        return $this;
    }

    public function setArrDate(string $date): self
    {
        $this->dateSetter($date, 'scheduledArrival');

        return $this;
    }

    public function setRtArrTime(string $time): self
    {
        $this->timeSetter($time, 'realtimeArrival');

        return $this;
    }

    public function setRtArrDate(string $date): self
    {
        $this->dateSetter($date, 'realtimeArrival');

        return $this;
    }

    public function isArrivalDelayed(): bool
    {
        if ($this->scheduledArrival instanceof DateTime && $this->realtimeArrival instanceof DateTime) {
            return $this->realtimeArrival > $this->scheduledArrival->add(new DateInterval('PT3M'));
        }

        return false;
    }
}

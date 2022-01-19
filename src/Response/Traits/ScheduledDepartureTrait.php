<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Traits;

use DateInterval;
use DateTime;
use DateTimeInterface;

trait ScheduledDepartureTrait
{
    /**
     * Scheduled departure for this stop.
     */
    public ?DateTimeInterface $scheduledDeparture = null;

    /**
     * Realtime departure for this stop.
     */
    public ?DateTimeInterface $realtimeDeparture = null;

    public function setDepTime(string $time): self
    {
        $this->timeSetter($time, 'scheduledDeparture');

        return $this;
    }

    public function setDepDate(string $date): self
    {
        $this->dateSetter($date, 'scheduledDeparture');

        return $this;
    }

    public function setRtDepTime(string $time): self
    {
        $this->timeSetter($time, 'realtimeDeparture');

        return $this;
    }

    public function setRtDepDate(string $date): self
    {
        $this->dateSetter($date, 'realtimeDeparture');

        return $this;
    }

    public function isDepartureDelayed(): bool
    {
        if ($this->scheduledDeparture instanceof DateTime && $this->realtimeDeparture instanceof DateTime) {
            return $this->realtimeDeparture > $this->scheduledDeparture->add(new DateInterval('PT3M'));
        }

        return false;
    }
}

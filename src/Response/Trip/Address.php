<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Trip;

use DateInterval;
use DateTime;
use Lsv\Rejseplan\Response\Traits\DateTimeSetter;
use Lsv\Rejseplan\Response\Traits\TrackTrait;

class Address
{
    use DateTimeSetter;
    use TrackTrait;

    /**
     * Name of the transportation.
     */
    public string $name = '';

    /**
     * Type of the transportation.
     */
    public string $type = '';

    public string $routeIdx = '';

    /**
     * Scheduled date.
     */
    public DateTime $scheduledDate;

    /**
     * Realtime date.
     */
    public DateTime $realtimeDate;

    public function __construct()
    {
        $this->realtimeDate = new DateTime();
        $this->scheduledDate = new DateTime();
    }

    public function setTime(string $time): self
    {
        $this->timeSetter($time, 'scheduledDate');

        return $this;
    }

    public function setDate(string $date): self
    {
        $this->dateSetter($date, 'scheduledDate');

        return $this;
    }

    public function setRtTime(string $time): self
    {
        $this->timeSetter($time, 'realtimeDate');

        return $this;
    }

    public function setRtDate(string $date): self
    {
        $this->dateSetter($date, 'realtimeDate');

        return $this;
    }

    public function isDelayed(): bool
    {
        $scheduled = $this->scheduledDate;

        return $this->realtimeDate > $scheduled->add(new DateInterval('PT3M'));
    }
}

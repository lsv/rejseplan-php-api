<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Traits;

use DateInterval;
use DateTime;
use DateTimeInterface;

trait ScheduledDateTrait
{
    /**
     * Scheduled date.
     */
    public ?DateTimeInterface $scheduledDate = null;

    /**
     * Realtime date.
     */
    public ?DateTimeInterface $realtimeDate = null;

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
        if ($this->realtimeDate instanceof DateTime && $this->scheduledDate instanceof DateTime) {
            return $this->realtimeDate > $this->scheduledDate->add(new DateInterval('PT3M'));
        }

        return false;
    }
}

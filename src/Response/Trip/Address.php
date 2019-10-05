<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Trip;

use DateInterval;
use DateTime;
use Lsv\Rejseplan\Response\Traits\DateTimeSetter;

class Address
{
    use DateTimeSetter;

    /**
     * Name of the transportation.
     *
     * @var string
     */
    public $name;

    /**
     * Type of the transportation.
     *
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $routeIdx;

    /**
     * Scheduled date.
     *
     * @var DateTime
     */
    public $scheduledDate;

    /**
     * Realtime date.
     *
     * @var DateTime
     */
    public $realtimeDate;

    /**
     * Scheduled track.
     *
     * @var string|null
     */
    public $scheduledTrack;

    /**
     * Realtime track.
     *
     * @var string|null
     */
    public $realtimeTrack;

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

    public function setTrack($track): self
    {
        $this->scheduledTrack = (string) $track;

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

    public function setRtTrack($track): self
    {
        $this->realtimeTrack = (string) $track;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRouteIdx(): string
    {
        return $this->routeIdx;
    }

    public function getScheduledDate(): DateTime
    {
        return $this->scheduledDate;
    }

    public function getRealtimeDate(): DateTime
    {
        return $this->realtimeDate;
    }

    public function getScheduledTrack(): ?string
    {
        return $this->scheduledTrack;
    }

    public function getRealtimeTrack(): ?string
    {
        return $this->realtimeTrack;
    }

    public function isDelayed(): bool
    {
        $scheduled = $this->scheduledDate;

        return $this->realtimeDate > $scheduled->add(new DateInterval('PT3M'));
    }

    public function isTrackChanged(): bool
    {
        return null !== $this->realtimeTrack && $this->scheduledTrack !== $this->realtimeTrack;
    }
}

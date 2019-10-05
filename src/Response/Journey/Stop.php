<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Journey;

use DateInterval;
use DateTime;
use DateTimeInterface;
use Lsv\Rejseplan\Response\CoordinateResponse;
use Lsv\Rejseplan\Response\Traits\DateTimeSetter;

class Stop
{
    use DateTimeSetter;

    /**
     * Stop name.
     *
     * @var string
     */
    public $name;

    /**
     * Stop coordinate.
     *
     * @var CoordinateResponse
     */
    public $coordinate;

    /**
     * Stop index.
     *
     * @var int
     */
    public $index;

    /**
     * Scheduled arrival for this stop.
     *
     * @var DateTime|null
     */
    public $scheduledArrival;

    /**
     * Scheduled arrival for this stop.
     *
     * @var DateTime|null
     */
    public $realtimeArrival;

    /**
     * Scheduled departure for this stop.
     *
     * @var DateTime|null
     */
    public $scheduledDeparture;

    /**
     * Realtime departure for this stop.
     *
     * @var DateTime|null
     */
    public $realtimeDeparture;

    /**
     * Scheduled track for this stop.
     *
     * @var int|null
     */
    public $scheduledTrack;

    /**
     * Realtime track for this stop.
     *
     * @var int|null
     */
    public $realtimeTrack;

    public function setRouteIdx($index): self
    {
        $this->index = (int) $index;

        return $this;
    }

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

    public function setTrack($track): self
    {
        $this->scheduledTrack = $track;

        return $this;
    }

    public function setRtTrack($track): self
    {
        $this->realtimeTrack = $track;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCoordinate(): CoordinateResponse
    {
        return $this->coordinate;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function getScheduledArrival(): ?DateTime
    {
        return $this->scheduledArrival;
    }

    public function getRealtimeArrival(): ?DateTime
    {
        return $this->realtimeArrival;
    }

    public function getScheduledDeparture(): ?DateTime
    {
        return $this->scheduledDeparture;
    }

    public function getRealtimeDeparture(): ?DateTime
    {
        return $this->realtimeDeparture;
    }

    public function getScheduledTrack(): ?int
    {
        return $this->scheduledTrack;
    }

    public function getRealtimeTrack(): ?int
    {
        return $this->realtimeTrack;
    }

    public function isArrivalDelayed(): bool
    {
        if ($this->scheduledArrival instanceof DateTimeInterface && $this->realtimeArrival instanceof DateTimeInterface) {
            $scheduled = $this->scheduledArrival;

            return $this->realtimeArrival > $scheduled->add(new DateInterval('PT3M'));
        }

        return false;
    }

    public function isDepartureDelayed(): bool
    {
        if ($this->scheduledDeparture instanceof DateTimeInterface && $this->realtimeDeparture instanceof DateTimeInterface) {
            $scheduled = $this->scheduledDeparture;

            return $this->realtimeDeparture > $scheduled->add(new DateInterval('PT3M'));
        }

        return false;
    }

    public function isTrackChanged(): bool
    {
        return $this->scheduledTrack !== $this->realtimeTrack;
    }
}

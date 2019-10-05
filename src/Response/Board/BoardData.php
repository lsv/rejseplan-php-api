<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Board;

use DateInterval;
use DateTime;
use Lsv\Rejseplan\Response\Traits\DateTimeSetter;

abstract class BoardData
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
     * Name of the stop.
     *
     * @var string
     */
    public $stop;

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
     * @var string
     */
    public $scheduledTrack;

    /**
     * Realtime track.
     *
     * @var string
     */
    public $realtimeTrack;

    /**
     * Messages.
     *
     * @var bool
     */
    public $hasMessages = false;

    /**
     * Url to the journey detail.
     *
     * @var string
     */
    public $journeyDetails;

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

    public function setJourneyDetailRef(array $ref): self
    {
        if (isset($ref['ref'])) {
            $this->journeyDetails = $ref['ref'];
        }

        return $this;
    }

    public function setMessages(string $hasMessages): self
    {
        $this->hasMessages = (bool) $hasMessages;

        return $this;
    }

    public function isDelayed(): bool
    {
        $scheduled = $this->scheduledDate;

        return $this->realtimeDate > $scheduled->add(new DateInterval('PT3M'));
    }

    public function isTrackChanged(): bool
    {
        return $this->scheduledTrack !== $this->realtimeTrack;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStop(): string
    {
        return $this->stop;
    }

    public function getScheduledDate(): DateTime
    {
        return $this->scheduledDate;
    }

    public function getRealtimeDate(): DateTime
    {
        return $this->realtimeDate;
    }

    public function getScheduledTrack(): string
    {
        return $this->scheduledTrack;
    }

    public function getRealtimeTrack(): string
    {
        return $this->realtimeTrack;
    }

    public function hasMessages(): bool
    {
        return $this->hasMessages;
    }

    public function getJourney(): string
    {
        return $this->journeyDetails;
    }
}

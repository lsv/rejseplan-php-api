<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Traits;

trait TrackTrait
{
    /**
     * Scheduled track for this stop.
     */
    public ?string $scheduledTrack = null;

    /**
     * Realtime track for this stop.
     */
    public ?string $realtimeTrack = null;

    public function isTrackChanged(): bool
    {
        return $this->scheduledTrack !== $this->realtimeTrack;
    }

    public function setRtTrack(null|int|string $track): self
    {
        if (null === $track) {
            $this->realtimeTrack = null;

            return $this;
        }

        $this->realtimeTrack = (string) $track;

        return $this;
    }

    public function setTrack(null|int|string $track): self
    {
        if (null === $track) {
            $this->scheduledTrack = null;

            return $this;
        }

        $this->scheduledTrack = (string) $track;

        return $this;
    }
}

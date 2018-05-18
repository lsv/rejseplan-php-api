<?php

namespace RejseplanApi\Services\Response\StationBoard;

use JMS\Serializer\Annotation as Serializer;
use RejseplanApi\Utils\JourneyDetailParser;

class BoardData
{
    /**
     * Name of the transportation.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * Type of the transportation.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $type;

    /**
     * Name of the stop.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $stop;

    /**
     * Scheduled date.
     *
     * @var \DateTime
     * @Serializer\Type("DateTime")
     */
    protected $scheduledDate;

    /**
     * Realtime date.
     *
     * @var \DateTime
     * @Serializer\Type("DateTime")
     */
    protected $realDate;

    /**
     * Is it delayed.
     *
     * @var bool
     * @Serializer\Type("boolean")
     */
    protected $delayed;

    /**
     * Scheduled track.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $scheduledTrack;

    /**
     * Realtime track.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $realTrack;

    /**
     * Has the track changed.
     *
     * @var bool|null
     * @Serializer\Type("boolean")
     */
    protected $trackChanged;

    /**
     * The real track used
     *
     * @var string|null
     */
    protected $usedTrack;

    /**
     * Messages.
     *
     * @var bool
     * @Serializer\Type("boolean")
     */
    protected $messages;

    /**
     * Final stop of the transportation.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $finalStop;

    /**
     * Origin of the transportation.
     *
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $origin;

    /**
     * The direction of the transportation.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $direction;

    /**
     * Url to the journey detail.
     *
     * @var string|null
     *
     * @Serializer\Type("string")
     */
    protected $journeyDetails;

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

    public function getScheduledDate(): \DateTime
    {
        return $this->scheduledDate;
    }

    public function getRealDate(): \DateTime
    {
        return $this->realDate;
    }

    public function isDelayed(): bool
    {
        return $this->delayed;
    }

    public function getScheduledTrack(): ?string
    {
        return $this->scheduledTrack;
    }

    public function getRealTrack(): ?string
    {
        return $this->realTrack;
    }

    public function isTrackChanged(): ?bool
    {
        return $this->trackChanged;
    }

    public function hasMessages(): bool
    {
        return $this->messages;
    }

    public function getFinalStop(): string
    {
        return $this->finalStop;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function getJourneyDetails(): ?string
    {
        return $this->journeyDetails;
    }

    public function getUsedTrack(): ?string
    {
        return $this->usedTrack;
    }

    public static function createFromArray(array $data): self
    {
        $obj = new self();
        foreach (['name', 'type', 'stop', 'finalStop', 'origin', 'direction'] as $setter) {
            $obj->{$setter} = $data[$setter] ?? null;
        }

        $obj->messages = false;
        if (isset($data['messages'])) {
            $obj->messages = (bool) $data['messages'];
        }

        if (isset($data['JourneyDetailRef']['ref'])) {
            $obj->journeyDetails = JourneyDetailParser::parseJourneyDetailUrl($data['JourneyDetailRef']['ref']);
        }

        self::setDate($obj, $data);
        self::setTrack($obj, $data);

        return $obj;
    }

    private static function setDate(self $obj, array $data): void
    {
        $obj->scheduledDate = date_create_from_format('d.m.y H:i', sprintf('%s %s', $data['date'], $data['time']));

        if (!isset($data['rtDate'])) {
            $data['rtDate'] = $data['date'];
        }

        if (!isset($data['rtTime'])) {
            $data['rtTime'] = $data['time'];
        }

        if ($data['date'] === $data['rtDate'] && $data['time'] === $data['rtTime']) {
            $obj->delayed = false;
        } else {
            $obj->delayed = true;
        }

        $obj->realDate = date_create_from_format('d.m.y H:i', sprintf('%s %s', $data['rtDate'], $data['rtTime']));
    }

    private static function setTrack(self $obj, array $data): void
    {
        $obj->trackChanged = null;

        if (isset($data['track'])) {
            $obj->trackChanged = true;
            $obj->scheduledTrack = $data['track'];
            $obj->usedTrack = $obj->scheduledTrack;
        }

        if (isset($data['rtTrack'])) {
            $obj->trackChanged = true;
            $obj->realTrack = $data['rtTrack'];
            $obj->usedTrack = $obj->realTrack;
        }

        if (!$obj->scheduledTrack && $obj->realTrack) {
            $obj->scheduledTrack = $obj->realTrack;
            $obj->usedTrack = $obj->scheduledTrack;
        }

        if ($obj->scheduledTrack === $obj->realTrack) {
            $obj->trackChanged = false;
        }
    }
}

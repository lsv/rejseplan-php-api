<?php

namespace RejseplanApi\Services\Response\Journey;

use JMS\Serializer\Annotation as Serializer;
use RejseplanApi\Utils\Coordinate;

class Stop
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * @var Coordinate
     * @Serializer\Type("RejseplanApi\Coordinate")
     */
    protected $coordinate;

    /**
     * @var int
     * @Serializer\Type("integer")
     */
    protected $index;

    /**
     * @var \DateTime|null
     * @Serializer\Type("DateTime")
     */
    protected $scheduledDeparture;

    /**
     * @var \DateTime|null
     * @Serializer\Type("DateTime")
     */
    protected $scheduledArrival;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $scheduledTrack;

    /**
     * @var \DateTime|null
     * @Serializer\Type("DateTime")
     */
    protected $realtimeDeparture;

    /**
     * @var \DateTime|null
     * @Serializer\Type("DateTime")
     */
    protected $realtimeArrival;

    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    protected $realtimeTrack;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     */
    protected $departureDelay;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     */
    protected $arrivalDelay;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     */
    protected $trackChanged;

    public function getName(): string
    {
        return $this->name;
    }

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function getScheduledDeparture(): ?\DateTime
    {
        return $this->scheduledDeparture;
    }

    public function getScheduledArrival(): ?\DateTime
    {
        return $this->scheduledArrival;
    }

    public function getScheduledTrack(): ?string
    {
        return $this->scheduledTrack;
    }

    public function getRealtimeDeparture(): ?\DateTime
    {
        return $this->realtimeDeparture;
    }

    public function getRealtimeArrival(): ?\DateTime
    {
        return $this->realtimeArrival;
    }

    public function getRealtimeTrack(): ?string
    {
        return $this->realtimeTrack;
    }

    public function isDepartureDelay(): bool
    {
        return $this->departureDelay;
    }

    public function isArrivalDelay(): bool
    {
        return $this->arrivalDelay;
    }

    public function isTrackChanged(): bool
    {
        return $this->trackChanged;
    }

    /**
     * @Serializer\VirtualProperty()
     */
    public function usesTrack(): bool
    {
        return !($this->getScheduledTrack() === null && $this->getRealtimeTrack() === null);
    }

    public static function createFromArray(array $data): self
    {
        $obj = new self();
        $obj->name = $data['name'];
        $obj->coordinate = new Coordinate($data['x'], $data['y']);
        $obj->index = $data['routeIdx'];

        self::setScheduled($obj, $data);
        self::setRealtime($obj, $data);
        self::setDepartureDelay($obj, $data);
        self::setArrivalDelay($obj, $data);

        $obj->trackChanged = $obj->getScheduledTrack() !== $obj->getRealtimeTrack();

        return $obj;
    }

    private static function setDepartureDelay(self $obj, array $data): void
    {
        self::setDelay($obj, $data, 'dep', 'departureDelay');
    }

    private static function setArrivalDelay(self $obj, array $data): void
    {
        self::setDelay($obj, $data, 'arr', 'arrivalDelay');
    }

    private static function setDelay(self $obj, array $data, $key, $property): void
    {
        $obj->{$property} = false;
        $rtKey = ucfirst($key);
        if (
            isset($data['rt' . $rtKey . 'Date'], $data['rt' . $rtKey . 'Time'], $data[$key . 'Date'], $data[$key . 'Time']) &&
            ($data['rt' . $rtKey . 'Date'] !== $data[$key . 'Date'] || $data['rt' . $rtKey . 'Time'] !== $data[$key . 'Time'])
        ) {
            $obj->{$property} = true;
        }
    }

    private static function setScheduled(self $obj, array $data): void
    {
        if (isset($data['depDate'], $data['depTime'])) {
            $obj->scheduledDeparture = self::createDate($data['depDate'], $data['depTime']);
        }

        if (isset($data['arrDate'], $data['arrTime'])) {
            $obj->scheduledArrival = self::createDate($data['arrDate'], $data['arrTime']);
        }

        if (isset($data['track'])) {
            $obj->scheduledTrack = $data['track'];
        }
    }

    private static function setRealtime(self $obj, array $data): void
    {
        $obj->realtimeDeparture = $obj->scheduledDeparture;
        if (isset($data['rtDepDate'], $data['rtDepTime'])) {
            $obj->realtimeDeparture = self::createDate($data['rtDepDate'], $data['rtDepTime']);
        }

        $obj->realtimeArrival = $obj->scheduledArrival;
        if (isset($data['rtArrDate'], $data['rtArrTime'])) {
            $obj->realtimeArrival = self::createDate($data['rtArrDate'], $data['rtArrTime']);
        }

        $obj->realtimeTrack = $obj->scheduledTrack;
        if (isset($data['rtTrack'])) {
            $obj->realtimeTrack = $data['rtTrack'];
        }
    }

    private static function createDate($date, $time): \DateTime
    {
        return date_create_from_format('d.m.y H:i', sprintf('%s %s', $date, $time));
    }
}

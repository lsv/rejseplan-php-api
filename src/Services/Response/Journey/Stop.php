<?php

namespace RejseplanApi\Services\Response\Journey;

use JMS\Serializer\Annotation as Serializer;
use RejseplanApi\Coordinate;

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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Coordinate
     */
    public function getCoordinate()
    {
        return $this->coordinate;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return \DateTime|null
     */
    public function getScheduledDeparture()
    {
        return $this->scheduledDeparture;
    }

    /**
     * @return \DateTime|null
     */
    public function getScheduledArrival()
    {
        return $this->scheduledArrival;
    }

    /**
     * @return null|string
     */
    public function getScheduledTrack()
    {
        return $this->scheduledTrack;
    }

    /**
     * @return \DateTime|null
     */
    public function getRealtimeDeparture()
    {
        return $this->realtimeDeparture;
    }

    /**
     * @return \DateTime|null
     */
    public function getRealtimeArrival()
    {
        return $this->realtimeArrival;
    }

    /**
     * @return null|string
     */
    public function getRealtimeTrack()
    {
        return $this->realtimeTrack;
    }

    /**
     * Get ScheduledDelay.
     *
     * @return bool
     */
    public function isDepartureDelay()
    {
        return $this->departureDelay;
    }

    /**
     * Get ArrivalDelay.
     *
     * @return bool
     */
    public function isArrivalDelay()
    {
        return $this->arrivalDelay;
    }

    /**
     * Get TrackChanged.
     *
     * @return bool
     */
    public function isTrackChanged()
    {
        return $this->trackChanged;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return bool
     */
    public function usesTrack()
    {
        return !($this->getScheduledTrack() === null && $this->getRealtimeTrack() === null);
    }

    /**
     * @param array $data
     *
     * @return Stop
     */
    public static function createFromArray(array $data)
    {
        $obj = new self();
        $obj->name = $data['name'];
        $obj->coordinate = new Coordinate($data['x'], $data['y']);
        $obj->index = $data['routeIdx'];

        self::setScheduled($obj, $data);
        self::setRealtime($obj, $data);
        self::setDepartureDelay($obj, $data);
        self::setArrivalDelay($obj, $data);

        $obj->trackChanged = $obj->getScheduledTrack() != $obj->getRealtimeTrack();

        return $obj;
    }

    private static function setDepartureDelay(Stop $obj, array $data)
    {
        self::setDelay($obj, $data, 'dep', 'departureDelay');
    }

    private static function setArrivalDelay(Stop $obj, array $data)
    {
        self::setDelay($obj, $data, 'arr', 'arrivalDelay');
    }

    private static function setDelay(Stop $obj, array $data, $key, $property)
    {
        $obj->{$property} = false;
        $rtKey = ucfirst($key);
        if (isset($data['rt'.$rtKey.'Date'], $data['rt'.$rtKey.'Time']) &&
            isset($data[$key.'Date'], $data[$key.'Time'])
        ) {
            if ($data['rt'.$rtKey.'Date'] != $data[$key.'Date'] ||
                $data['rt'.$rtKey.'Time'] != $data[$key.'Time']
            ) {
                $obj->{$property} = true;
            }
        }
    }

    private static function setScheduled(Stop $obj, array $data)
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

    private static function setRealtime(Stop $obj, array $data)
    {
        if (isset($data['rtDepDate'], $data['rtDepTime'])) {
            $obj->realtimeDeparture = self::createDate($data['rtDepDate'], $data['rtDepTime']);
        } else {
            $obj->realtimeDeparture = $obj->scheduledDeparture;
        }

        if (isset($data['rtArrDate'], $data['rtArrTime'])) {
            $obj->realtimeArrival = self::createDate($data['rtArrDate'], $data['rtArrTime']);
        } else {
            $obj->realtimeArrival = $obj->scheduledArrival;
        }

        if (isset($data['rtTrack'])) {
            $obj->realtimeTrack = $data['rtTrack'];
        } else {
            $obj->realtimeTrack = $obj->scheduledTrack;
        }
    }

    private static function createDate($date, $time)
    {
        return date_create_from_format('d.m.y H:i', sprintf('%s %s', $date, $time));
    }
}

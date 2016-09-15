<?php

namespace RejseplanApi\Services\Response\StationBoard;

use JMS\Serializer\Annotation as Serializer;

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
     * @var string
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
     * @var string
     * @Serializer\Type("string")
     */
    protected $journeyDetails;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * @return \DateTime
     */
    public function getScheduledDate()
    {
        return $this->scheduledDate;
    }

    /**
     * @return \DateTime
     */
    public function getRealDate()
    {
        return $this->realDate;
    }

    /**
     * @return bool
     */
    public function isDelayed()
    {
        return $this->delayed;
    }

    /**
     * @return string
     */
    public function getScheduledTrack()
    {
        return $this->scheduledTrack;
    }

    /**
     * @return string
     */
    public function getRealTrack()
    {
        return $this->realTrack;
    }

    /**
     * @return bool|null
     */
    public function isTrackChanged()
    {
        return $this->trackChanged;
    }

    /**
     * @return bool
     */
    public function hasMessages()
    {
        return $this->messages;
    }

    /**
     * @return string
     */
    public function getFinalStop()
    {
        return $this->finalStop;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @return string
     */
    public function getJourneyDetails()
    {
        return $this->journeyDetails;
    }

    /**
     * @param array $data
     *
     * @return BoardData
     */
    public static function createFromArray(array $data)
    {
        $obj = new self();
        $obj->name = $data['name'];
        $obj->type = $data['type'];
        $obj->stop = $data['stop'];

        if (isset($data['finalStop'])) {
            $obj->finalStop = $data['finalStop'];
        }

        if (isset($data['origin'])) {
            $obj->origin = $data['origin'];
        }

        if (isset($data['direction'])) {
            $obj->direction = $data['direction'];
        }

        $obj->messages = false;
        if (isset($data['messages'])) {
            $obj->messages = (bool) $data['messages'];
        }

        if (isset($data['JourneyDetailRef'], $data['JourneyDetailRef']['ref'])) {
            $obj->journeyDetails = $data['JourneyDetailRef']['ref'];
        }

        self::setDate($obj, $data);
        self::setTrack($obj, $data);

        return $obj;
    }

    /**
     * @param BoardData $obj
     * @param array     $data
     */
    private static function setDate(BoardData $obj, array $data)
    {
        $obj->scheduledDate = date_create_from_format('d.m.y H:i', sprintf('%s %s', $data['date'], $data['time']));

        if (!isset($data['rtDate'])) {
            $data['rtDate'] = $data['date'];
        }

        if (!isset($data['rtTime'])) {
            $data['rtTime'] = $data['time'];
        }

        if ($data['date'] == $data['rtDate'] && $data['time'] == $data['rtTime']) {
            $obj->delayed = false;
        } else {
            $obj->delayed = true;
        }

        $obj->realDate = date_create_from_format('d.m.y H:i', sprintf('%s %s', $data['rtDate'], $data['rtTime']));
    }

    /**
     * @param BoardData $obj
     * @param array     $data
     */
    private static function setTrack(BoardData $obj, array $data)
    {
        $obj->trackChanged = null;

        if (isset($data['track']) || isset($data['rtTrack'])) {
            if (isset($data['track'])) {
                $obj->scheduledTrack = $data['track'];
            }

            if (isset($data['rtTrack'])) {
                $obj->realTrack = $data['rtTrack'];
            }

            if (!$obj->scheduledTrack) {
                $obj->scheduledTrack = $obj->realTrack;
            }

            if ($obj->scheduledTrack == $obj->realTrack) {
                $obj->trackChanged = false;
            } else {
                $obj->trackChanged = true;
            }
        }
    }
}

<?php

namespace RejseplanApi\Services\Response\Trip;

use JMS\Serializer\Annotation as Serializer;

class Place
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $type;

    /**
     * Station index on the journey details.
     *
     * @var int
     * @Serializer\Type("integer")
     */
    protected $routeIdx;

    /**
     * Station track.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $track;

    /**
     * Station real track.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $rtTrack;

    /**
     * Date on this place.
     *
     * @var \DateTime
     * @Serializer\Type("\DateTime")
     */
    protected $date;

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
     * @return int
     */
    public function getRouteIdx()
    {
        return $this->routeIdx;
    }

    /**
     * @return string
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * @return string
     */
    public function getRtTrack()
    {
        return $this->rtTrack;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param array $data
     *
     * @return Place
     */
    public static function createFromArray(array $data)
    {
        $obj = new self();
        $obj->name = $data['name'];
        $obj->type = $data['type'];
        $obj->date = date_create_from_format('d.m.y H:i', sprintf('%s %s', $data['date'], $data['time']));

        if (isset($data['routeIdx'])) {
            $obj->routeIdx = $data['routeIdx'];
        }

        if (isset($data['track']) || isset($data['rtTrack'])) {
            if (isset($data['track'], $data['rtTrack'])) {
                $obj->track = $data['track'];
                $obj->rtTrack = $data['rtTrack'];
            } else {
                if (isset($data['track'])) {
                    $obj->track = $data['track'];
                    $obj->rtTrack = $data['track'];
                }

                if (isset($data['rtTrack'])) {
                    $obj->track = $data['rtTrack'];
                    $obj->rtTrack = $data['rtTrack'];
                }
            }
        }

        return $obj;
    }
}

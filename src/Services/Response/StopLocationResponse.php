<?php

namespace RejseplanApi\Services\Response;

use JMS\Serializer\Annotation as Serializer;
use RejseplanApi\Coordinate;

class StopLocationResponse
{
    /**
     * The ID of this stop.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $id;

    /**
     * Contains the name of this stop or station.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * The WGS84 coordinate.
     *
     * @var Coordinate
     * @Serializer\Type("RejseplanApi\Coordinate")
     */
    protected $coordinate;

    /**
     * Distance in meters.
     *
     * @var int
     * @Serializer\Type("integer")
     */
    protected $distance;

    /**
     * @return null|string
     */
    public function getId()
    {
        return $this->id;
    }

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
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param array $data
     *
     * @return StopLocationResponse
     */
    public static function createFromArray(array $data)
    {
        $obj = new self();
        $obj->id = $data['id'];
        $obj->name = $data['name'];
        $obj->coordinate = new Coordinate($data['x'], $data['y']);
        $obj->distance = $data['distance'];

        return $obj;
    }
}

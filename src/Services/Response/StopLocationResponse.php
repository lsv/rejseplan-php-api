<?php

namespace RejseplanApi\Services\Response;

use RejseplanApi\Coordinate;

class StopLocationResponse
{
    /**
     * The ID of this stop, null if not a stop.
     *
     * @var string|null
     */
    protected $id;

    /**
     * Contains the name of this stop or station.
     *
     * @var string
     */
    protected $name;

    /**
     * The WGS84 coordinate.
     *
     * @var Coordinate
     */
    protected $coordinate;

    /**
     * Distance in meters.
     *
     * @var int
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

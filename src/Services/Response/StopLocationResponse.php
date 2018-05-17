<?php

namespace RejseplanApi\Services\Response;

use JMS\Serializer\Annotation as Serializer;
use RejseplanApi\Utils\Coordinate;

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

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCoordinate(): Coordinate
    {
        return $this->coordinate;
    }

    public function getDistance(): int
    {
        return $this->distance;
    }

    public static function createFromArray(array $data): StopLocationResponse
    {
        $obj = new self();
        $obj->id = $data['id'];
        $obj->name = $data['name'];
        $obj->coordinate = new Coordinate($data['x'], $data['y']);
        $obj->distance = $data['distance'];

        return $obj;
    }
}

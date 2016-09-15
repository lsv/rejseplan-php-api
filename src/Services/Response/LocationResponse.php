<?php

namespace RejseplanApi\Services\Response;

use JMS\Serializer\Annotation as Serializer;
use RejseplanApi\Coordinate;

class LocationResponse
{
    const LOCATIONTYPE_ADDRESS = 'ADR';
    const LOCATIONTYPE_POI = 'POI';
    const LOCATIONTYPE_STOP = 'STOP';

    /**
     * The ID of this stop, null if not a stop.
     *
     * @var string|null
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
     * Coordinates of this stop or station.
     *
     * @var Coordinate
     * @Serializer\Type("RejseplanApi\Coordinate")
     */
    protected $coordinate;

    /**
     * Type of the location.
     *
     * @var string
     */
    protected $type = self::LOCATIONTYPE_STOP;

    /**
     * Generate a LocationResponse.
     *
     * @param string     $name
     * @param Coordinate $coordinate
     */
    public function __construct($name, Coordinate $coordinate = null)
    {
        $this->name = $name;
        $this->coordinate = $coordinate;
    }

    /**
     * Get id of this location - only sations have a ID
     * POI or addresses does not have a ID, and therefor will return null.
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name of this location.
     *
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
     * Is this location a stop (bus stop, train station etc).
     *
     * @return bool
     * @Serializer\VirtualProperty()
     */
    public function isStop()
    {
        return $this->getType() === self::LOCATIONTYPE_STOP;
    }

    /**
     * Is this location a address.
     *
     * @return bool
     * @Serializer\VirtualProperty()
     */
    public function isAddress()
    {
        return $this->getType() === self::LOCATIONTYPE_ADDRESS;
    }

    /**
     * Is this location a POI.
     *
     * @return bool
     * @Serializer\VirtualProperty()
     */
    public function isPOI()
    {
        return $this->getType() === self::LOCATIONTYPE_POI;
    }

    /**
     * @return string
     */
    protected function getType()
    {
        return $this->type;
    }

    /**
     * @param array $data
     *
     * @return LocationResponse
     */
    public static function createFromArray(array $data)
    {
        $obj = new self($data['name'], new Coordinate($data['x'], $data['y']));
        if (isset($data['id'])) {
            $obj->id = $data['id'];
        }

        if (isset($data['type'])) {
            $obj->type = $data['type'];
        }

        return $obj;
    }
}

<?php

namespace RejseplanApi\Services\Response;

use JMS\Serializer\Annotation as Serializer;
use RejseplanApi\Utils\Coordinate;

class LocationResponse
{
    public const LOCATIONTYPE_ADDRESS = 'ADR';
    public const LOCATIONTYPE_POI = 'POI';
    public const LOCATIONTYPE_STOP = 'STOP';

    /**
     * The ID of this stop, null if not a stop.
     *
     * @var string|null
     *
     * @Serializer\Type("string")
     */
    protected $id;

    /**
     * Contains the name of this stop or station.
     *
     * @var string
     *
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * Coordinates of this stop or station.
     *
     * @var Coordinate|null
     *
     * @Serializer\Type("RejseplanApi\Coordinate")
     */
    protected $coordinate;

    /**
     * Type of the location.
     *
     * @var string
     */
    protected $type = self::LOCATIONTYPE_STOP;

    public function __construct(string $name, ?Coordinate $coordinate = null)
    {
        $this->name = $name;
        $this->coordinate = $coordinate;
    }

    /**
     * Get id of this location - only sations have a ID
     * POI or addresses does not have a ID, and therefor will return null.
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Get name of this location.
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getCoordinate(): ?Coordinate
    {
        return $this->coordinate;
    }

    /**
     * Is this location a stop (bus stop, train station etc).
     *
     * @Serializer\VirtualProperty()
     */
    public function isStop(): bool
    {
        return $this->getType() === self::LOCATIONTYPE_STOP;
    }

    /**
     * Is this location a address.
     *
     * @Serializer\VirtualProperty()
     */
    public function isAddress(): bool
    {
        return $this->getType() === self::LOCATIONTYPE_ADDRESS;
    }

    /**
     * Is this location a POI.
     *
     * @Serializer\VirtualProperty()
     */
    public function isPoi(): bool
    {
        return $this->getType() === self::LOCATIONTYPE_POI;
    }

    protected function getType(): string
    {
        return $this->type;
    }

    public static function createFromArray(array $data): self
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

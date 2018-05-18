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
     * @Serializer\Type("RejseplanApi\Utils\Coordinate")
     */
    protected $coordinate;

    /**
     * Type of the location.
     *
     * @var string
     */
    protected $type = self::LOCATIONTYPE_STOP;

    /**
     * Is the location a stop
     *
     * @var bool
     */
    protected $isStop = false;

    /**
     * Is the location an address
     *
     * @var bool
     */
    protected $isAddress = false;

    /**
     * Is the location a POI
     *
     * @var bool
     */
    protected $isPoi = false;

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

    public function isStop(): bool
    {
        return $this->isStop;
    }

    public function isAddress(): bool
    {
        return $this->isAddress;
    }

    public function isPoi(): bool
    {
        return $this->isPoi;
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

        $obj->isStop = $obj->type === self::LOCATIONTYPE_STOP;
        $obj->isAddress = $obj->type === self::LOCATIONTYPE_ADDRESS;
        $obj->isPoi = $obj->type === self::LOCATIONTYPE_POI;

        return $obj;
    }
}

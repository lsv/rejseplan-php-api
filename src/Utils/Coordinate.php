<?php

namespace RejseplanApi\Utils;

use JMS\Serializer\Annotation as Serializer;

class Coordinate
{
    private const INT_INVERT = 1000000;

    /**
     * Latitude.
     *
     * @var float
     *
     * @Serializer\Type("float")
     */
    protected $latitude;

    /**
     * Longitude.
     *
     * @var float
     *
     * @Serializer\Type("float")
     */
    protected $longitude;

    public function __construct(?float $latitude = null, ?float $longitude = null)
    {
        if ($latitude) {
            $this->setLatitude($latitude);
        }

        if ($longitude) {
            $this->setLongitude($longitude);
        }
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLatitudeAsInt(): float
    {
        return $this->getLatitude() * self::INT_INVERT;
    }

    public function setLatitude(float $latitude): Coordinate
    {
        $this->latitude = $this->setCoordinate($latitude);

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getLongitudeAsInt(): float
    {
        return $this->getLongitude() * self::INT_INVERT;
    }

    public function setLongitude(float $longitude): Coordinate
    {
        $this->longitude = $this->setCoordinate($longitude);

        return $this;
    }

    /**
     * @return string
     *
     * @Serializer\SerializedName("coordinates")
     * @Serializer\VirtualProperty()
     */
    public function __toString()
    {
        return sprintf('%s,%s', $this->getLatitude(), $this->getLongitude());
    }

    private function setCoordinate($coordinate)
    {
        $coordinate = (float) $coordinate;
        $coordinate = strpos($coordinate, '.') === false ? ($coordinate / self::INT_INVERT) : $coordinate;

        return $coordinate;
    }
}

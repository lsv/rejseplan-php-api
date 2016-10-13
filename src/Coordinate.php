<?php

namespace RejseplanApi;

use JMS\Serializer\Annotation as Serializer;

class Coordinate
{
    const INT_INVERT = 1000000;

    /**
     * Latitude.
     *
     * @var float
     * @Serializer\Type("float")
     */
    protected $latitude;

    /**
     * Longitude.
     *
     * @var float
     * @Serializer\Type("float")
     */
    protected $longitude;

    /**
     * Coordinate constructor.
     *
     * @param string|float|null $latitude
     * @param string|float|null $longitude
     */
    public function __construct($latitude = null, $longitude = null)
    {
        if ($latitude) {
            $this->setLatitude($latitude);
        }

        if ($longitude) {
            $this->setLongitude($longitude);
        }
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return int
     */
    public function getLatitudeAsInt()
    {
        return $this->getLatitude() * self::INT_INVERT;
    }

    /**
     * @param float $latitude
     *
     * @return Coordinate
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $this->setCoordinate($latitude);

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLongitudeAsInt()
    {
        return $this->getLongitude() * self::INT_INVERT;
    }

    /**
     * @param float $longitude
     *
     * @return Coordinate
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $this->setCoordinate($longitude);

        return $this;
    }

    /**
     * @return string
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

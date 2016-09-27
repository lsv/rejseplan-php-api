<?php

namespace RejseplanApi;

use JMS\Serializer\Annotation as Serializer;

class Coordinate
{
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
        return (float) $this->latitude;
    }

    /**
     * @param float $latitude
     *
     * @return Coordinate
     */
    public function setLatitude($latitude)
    {
        $this->latitude = strpos($latitude, '.') === false ? (float) $latitude / 1000000 : $latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return (float) $this->longitude;
    }

    /**
     * @param float $longitude
     *
     * @return Coordinate
     */
    public function setLongitude($longitude)
    {
        $this->longitude = strpos($longitude, '.') === false ? (float) $longitude / 1000000 : $longitude;

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
}

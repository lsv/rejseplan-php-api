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
     * @param float $latitude
     *
     * @return Coordinate
     */
    public function setLatitude($latitude)
    {
        $this->latitude = strpos($latitude, '.') === false ? $latitude / 1000000 : $latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     *
     * @return Coordinate
     */
    public function setLongitude($longitude)
    {
        $this->longitude = strpos($longitude, '.') === false ? $longitude / 1000000 : $longitude;

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

<?php

namespace RejseplanApi;

class Coordinate
{
    /**
     * X-coordinate.
     *
     * @var float
     */
    protected $xCoordinate;

    /**
     * Y-coordinate.
     *
     * @var float
     */
    protected $yCoordinate;

    public function __construct($x = null, $y = null)
    {
        if ($x) {
            $this->setXCoordinate($x);
        }

        if ($y) {
            $this->setYCoordinate($y);
        }
    }

    /**
     * @return float
     */
    public function getXCoordinate()
    {
        return $this->xCoordinate;
    }

    /**
     * @param float $xCoordinate
     *
     * @return Coordinate
     */
    public function setXCoordinate($xCoordinate)
    {
        $this->xCoordinate = strpos($xCoordinate, '.') === false ? $xCoordinate / 1000000 : $xCoordinate;

        return $this;
    }

    /**
     * @return float
     */
    public function getYCoordinate()
    {
        return $this->yCoordinate;
    }

    /**
     * @param float $yCoordinate
     *
     * @return Coordinate
     */
    public function setYCoordinate($yCoordinate)
    {
        $this->yCoordinate = strpos($yCoordinate, '.') === false ? $yCoordinate / 1000000 : $yCoordinate;

        return $this;
    }

    public function __toString()
    {
        return sprintf('%s,%s', $this->getXCoordinate(), $this->getYCoordinate());
    }
}

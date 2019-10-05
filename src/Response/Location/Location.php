<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Location;

use Lsv\Rejseplan\Response\CoordinateResponse;

abstract class Location
{
    /**
     * Contains the name of this stop or station.
     *
     * @var string
     */
    public $name;

    /**
     * Coordinates of this stop or station.
     *
     * @var CoordinateResponse|null
     */
    public $coordinate;

    public function getName(): string
    {
        return $this->name;
    }

    public function getCoordinate(): ?CoordinateResponse
    {
        return $this->coordinate;
    }
}

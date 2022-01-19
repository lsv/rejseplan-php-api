<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Location;

use Lsv\Rejseplan\Response\CoordinateResponse;

abstract class Location
{
    /**
     * Contains the name of this stop or station.
     */
    public string $name = '';

    /**
     * Coordinates of this stop or station.
     */
    public ?CoordinateResponse $coordinate = null;
}

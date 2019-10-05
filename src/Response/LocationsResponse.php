<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response;

use Lsv\Rejseplan\Response\Location\Address;
use Lsv\Rejseplan\Response\Location\Poi;
use Lsv\Rejseplan\Response\Location\Stop;

class LocationsResponse
{
    /**
     * @var Stop[]
     */
    public $stops;

    /**
     * @var Poi[]
     */
    public $pois;

    /**
     * @var Address[]
     */
    public $addresses;

    /**
     * @return Stop[]
     */
    public function getStops(): array
    {
        return $this->stops;
    }

    /**
     * @return Poi[]
     */
    public function getPois(): array
    {
        return $this->pois;
    }

    /**
     * @return Address[]
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }
}

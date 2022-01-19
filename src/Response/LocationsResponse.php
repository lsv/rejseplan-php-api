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
    public array $stops = [];

    /**
     * @var Poi[]
     */
    public array $pois = [];

    /**
     * @var Address[]
     */
    public array $addresses = [];
}

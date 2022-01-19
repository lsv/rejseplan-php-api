<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response;

use Lsv\Rejseplan\Response\Trip\Trip;

class TripResponse
{
    /**
     * @var Trip[]
     */
    public array $trips = [];
}

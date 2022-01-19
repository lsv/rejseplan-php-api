<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response;

use Lsv\Rejseplan\Response\Board\DepartureBoardData;

class DepartureBoardResponse
{
    /**
     * The list of arrivals to the location.
     *
     * @var DepartureBoardData[]
     */
    public array $departures = [];
}

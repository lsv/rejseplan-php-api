<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response;

use Lsv\Rejseplan\Response\NearbyStop\Stop;

class NearbyStopsResponse
{
    /**
     * @var Stop[]
     */
    public $stops;

    /**
     * @return Stop[]
     */
    public function getStops(): array
    {
        return $this->stops;
    }
}

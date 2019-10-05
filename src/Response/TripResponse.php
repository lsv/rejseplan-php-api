<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response;

use Lsv\Rejseplan\Response\Trip\Trip;

class TripResponse
{
    /**
     * @var Trip[]
     */
    public $trips;

    /**
     * @return Trip[]
     */
    public function getTrips(): array
    {
        return $this->trips;
    }
}

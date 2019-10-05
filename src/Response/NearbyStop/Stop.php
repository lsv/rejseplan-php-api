<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\NearbyStop;

use Lsv\Rejseplan\Response\Location\Stop as LocationStop;

class Stop extends LocationStop
{
    /**
     * @var string
     */
    public $distance;

    public function getDistance(): string
    {
        return $this->distance;
    }
}

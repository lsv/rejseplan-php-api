<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\NearbyStop;

use Lsv\Rejseplan\Response\Location\Stop as LocationStop;

class Stop extends LocationStop
{
    public string $distance;
}

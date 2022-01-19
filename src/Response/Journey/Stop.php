<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Journey;

use Lsv\Rejseplan\Response\CoordinateResponse;
use Lsv\Rejseplan\Response\Traits\DateTimeSetter;
use Lsv\Rejseplan\Response\Traits\ScheduledArrivalTrait;
use Lsv\Rejseplan\Response\Traits\ScheduledDepartureTrait;
use Lsv\Rejseplan\Response\Traits\TrackTrait;

class Stop
{
    use DateTimeSetter;
    use TrackTrait;
    use ScheduledArrivalTrait;
    use ScheduledDepartureTrait;

    /**
     * Stop name.
     */
    public string $name = '';

    /**
     * Stop coordinate.
     */
    public ?CoordinateResponse $coordinate = null;

    /**
     * Stop index.
     */
    public string $routeIdx = '';
}

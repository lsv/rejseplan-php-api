<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response;

use Lsv\Rejseplan\Response\Board\ArrivalBoardData;

class ArrivalBoardResponse
{
    /**
     * The list of arrivals to the location.
     *
     * @var ArrivalBoardData[]
     */
    public array $arrivals = [];
}

<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Board;

class DepartureBoardData extends BoardData
{
    /**
     * Final stop of the transportation.
     */
    public string $finalStop;

    /**
     * The direction of the transportation.
     */
    public string $direction;
}

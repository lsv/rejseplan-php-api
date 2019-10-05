<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Board;

class ArrivalBoardData extends BoardData
{
    /**
     * Origin of the transportation.
     *
     * @var string
     */
    public $origin;

    public function getOrigin(): string
    {
        return $this->origin;
    }
}

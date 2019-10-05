<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Board;

class DepartureBoardData extends BoardData
{
    /**
     * Final stop of the transportation.
     *
     * @var string
     */
    public $finalStop;

    /**
     * The direction of the transportation.
     *
     * @var string
     */
    public $direction;

    public function getFinalStop(): string
    {
        return $this->finalStop;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}

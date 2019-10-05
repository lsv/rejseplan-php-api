<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Trip;

class Trip
{
    /**
     * @var Leg[]
     */
    public $legs;

    /**
     * @return Leg[]
     */
    public function getLegs(): array
    {
        return $this->legs;
    }
}

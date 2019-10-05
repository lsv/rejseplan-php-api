<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Location;

class Stop extends Location
{
    /**
     * The ID of this stop, null if not a stop.
     *
     * @var string|null
     */
    public $id;

    public function getId(): ?string
    {
        return $this->id;
    }
}

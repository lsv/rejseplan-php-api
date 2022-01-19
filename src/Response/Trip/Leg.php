<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Trip;

use Lsv\Rejseplan\Response\Traits\JourneyDetailsTrait;

class Leg
{
    use JourneyDetailsTrait;

    public string $name = '';

    public string $type = '';

    public string $line = '';

    public Address $origin;

    public Address $destination;

    /**
     * @var Note[]
     */
    public array $notes = [];

    /**
     * @var Message[]
     */
    public array $messages = [];
}

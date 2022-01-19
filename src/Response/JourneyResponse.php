<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response;

use Lsv\Rejseplan\Response\Journey\Message;
use Lsv\Rejseplan\Response\Journey\Note;
use Lsv\Rejseplan\Response\Journey\Stop;

class JourneyResponse
{
    /**
     * The stops for this journey.
     *
     * @var Stop[]
     */
    public array $stops = [];

    /**
     * The name of this journey.
     */
    public string $name;

    /**
     * The type of journey.
     */
    public string $type;

    /**
     * Notes for the journey.
     *
     * @var Note[]
     */
    public array $notes = [];

    /**
     * Messages for the journey.
     *
     * @var Message[]
     */
    public array $messages = [];
}

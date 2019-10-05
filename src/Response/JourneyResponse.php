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
    public $stops = [];

    /**
     * The name of this journey.
     *
     * @var string
     */
    public $name;

    /**
     * The type of journey.
     *
     * @var string
     */
    public $type;

    /**
     * Notes for the journey.
     *
     * @var Note[]
     */
    public $notes = [];

    /**
     * Messages for the journey.
     *
     * @var Message[]
     */
    public $messages = [];

    /**
     * @return Stop[]
     */
    public function getStops(): array
    {
        return $this->stops;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Note[]
     */
    public function getNotes(): array
    {
        return $this->notes;
    }

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}

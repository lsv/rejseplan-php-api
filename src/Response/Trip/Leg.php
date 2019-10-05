<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Trip;

class Leg
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $line;

    /**
     * @var Address
     */
    public $origin;

    /**
     * @var Address
     */
    public $destination;

    /**
     * @var Note[]
     */
    public $notes;

    /**
     * @var Message[]
     */
    public $messages;

    /**
     * @var string
     */
    public $journey;

    public function setJourneyDetailRef($ref): self
    {
        if (isset($ref['ref'])) {
            $this->journey = $ref['ref'];
        }

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLine(): string
    {
        return $this->line;
    }

    public function getOrigin(): Address
    {
        return $this->origin;
    }

    public function getDestination(): Address
    {
        return $this->destination;
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

    public function getJourney(): string
    {
        return $this->journey;
    }
}

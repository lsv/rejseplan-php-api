<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Board;

use Lsv\Rejseplan\Response\Traits\DateTimeSetter;
use Lsv\Rejseplan\Response\Traits\JourneyDetailsTrait;
use Lsv\Rejseplan\Response\Traits\ScheduledDateTrait;
use Lsv\Rejseplan\Response\Traits\TrackTrait;

abstract class BoardData
{
    use DateTimeSetter;
    use TrackTrait;
    use JourneyDetailsTrait;
    use ScheduledDateTrait;

    /**
     * Name of the transportation.
     */
    public string $name = '';

    /**
     * Type of the transportation.
     */
    public string $type = '';

    /**
     * Name of the stop.
     */
    public string $stop = '';

    /**
     * Messages.
     */
    public bool $hasMessages = false;

    public function setMessages(string $hasMessages): self
    {
        $this->hasMessages = (bool) $hasMessages;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Journey;

class Message
{
    /**
     * Message header.
     *
     * @var string
     */
    public $header;

    /**
     * Message text.
     *
     * @var string
     */
    public $text;

    public function getHeader(): string
    {
        return $this->header;
    }

    public function getText(): string
    {
        return $this->text;
    }
}

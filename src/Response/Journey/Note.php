<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Response\Journey;

class Note
{
    /**
     * @var string
     */
    public $text;

    public function getText(): string
    {
        return $this->text;
    }
}

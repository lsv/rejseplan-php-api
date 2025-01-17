<?php

namespace Lsv\Wasl\Generator\Model;

final readonly class OptionModel
{
    public function __construct(
        public string $name,
        private string $type,
        public bool $required,
    )
    {
    }

    public function getType(): string
    {
        return sprintf('%s%s', $this->required ? '' : '?', $this->type);
    }
}
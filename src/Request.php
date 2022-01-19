<?php

declare(strict_types=1);

namespace Lsv\Rejseplan;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface Request
{
    /**
     * @phpstan-return array<array-key, mixed>
     */
    public function getQuery(): array;

    public function getRequest(): ?RequestInterface;

    public function getResponse(): ?ResponseInterface;

    public function request(): mixed;
}

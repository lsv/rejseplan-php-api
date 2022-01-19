<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HttpResponseObject implements ResponseInterface
{
    private string $body;
    private int $httpCode;

    public function __construct(string $body, int $status)
    {
        $this->body = $body;
        $this->httpCode = $status;
    }

    public function getProtocolVersion(): void
    {
    }

    public function withProtocolVersion($version): void
    {
    }

    public function getHeaders(): void
    {
    }

    public function hasHeader($name): void
    {
    }

    public function getHeader($name): void
    {
    }

    public function getHeaderLine($name): void
    {
    }

    public function withHeader($name, $value): void
    {
    }

    public function withAddedHeader($name, $value): void
    {
    }

    public function withoutHeader($name): void
    {
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): void
    {
    }

    public function getStatusCode(): int
    {
        return $this->httpCode;
    }

    public function withStatus($code, $reasonPhrase = ''): void
    {
    }

    public function getReasonPhrase(): void
    {
    }
}

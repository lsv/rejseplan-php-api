<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest;

use Http\Mock\Client;
use Lsv\Rejseplan\AbstractRequest;
use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{
    protected function setClient(string $filename, int $status = 200): void
    {
        $client = new Client();
        $client->addResponse(
            new HttpResponseObject(file_get_contents($filename), $status)
        );

        AbstractRequest::setClient($client);
    }
}

<?php

declare(strict_types=1);

namespace Lsv\Rejseplan;

use Lsv\Rejseplan\Response\DepartureBoardResponse;

class DepartureBoard extends AbstractBoard
{
    public function request(): DepartureBoardResponse
    {
        return $this->makeRequest();
    }

    protected function getUrl(): string
    {
        return 'departureBoard';
    }

    protected function getResponseClass(): string
    {
        return DepartureBoardResponse::class;
    }

    protected function makeResponse(string $response): string
    {
        $json = json_decode($response, true, flags: JSON_THROW_ON_ERROR)['DepartureBoard']['Departure'];
        if (isset($json['name'])) {
            $json = [$json];
        }

        return json_encode(['departures' => $json], JSON_THROW_ON_ERROR);
    }
}

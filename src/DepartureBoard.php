<?php

declare(strict_types=1);

namespace Lsv\Rejseplan;

use Lsv\Rejseplan\Response\DepartureBoardResponse;
use Lsv\Rejseplan\Response\Location\Stop;

class DepartureBoard extends AbstractBoard
{
    /**
     * @param string|int|Stop $location
     *
     * @return DepartureBoardResponse
     */
    public function request($location): DepartureBoardResponse
    {
        $this->setLocation($location);

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

    protected function getResponse(string $response): string
    {
        $json = json_decode($response, true, 512,
            JSON_THROW_ON_ERROR)['DepartureBoard']['Departure'];
        if (isset($json['name'])) {
            $json = [$json];
        }

        return json_encode(['departures' => $json], JSON_THROW_ON_ERROR);
    }
}

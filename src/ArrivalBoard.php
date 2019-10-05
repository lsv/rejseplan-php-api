<?php

declare(strict_types=1);

namespace Lsv\Rejseplan;

use Lsv\Rejseplan\Response\ArrivalBoardResponse;
use Lsv\Rejseplan\Response\Location\Stop;

class ArrivalBoard extends AbstractBoard
{
    /**
     * @param string|int|Stop $location
     *
     * @return ArrivalBoardResponse
     */
    public function request($location): ArrivalBoardResponse
    {
        $this->setLocation($location);

        return $this->makeRequest();
    }

    protected function getUrl(): string
    {
        return 'arrivalBoard';
    }

    protected function getResponse(string $response): string
    {
        $json = json_decode($response, true, 512,
            JSON_THROW_ON_ERROR)['ArrivalBoard']['Arrival'];
        if (isset($json['name'])) {
            $json = [$json];
        }

        return json_encode(['arrivals' => $json], JSON_THROW_ON_ERROR);
    }

    protected function getResponseClass(): string
    {
        return ArrivalBoardResponse::class;
    }
}

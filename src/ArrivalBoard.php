<?php

declare(strict_types=1);

namespace Lsv\Rejseplan;

use Lsv\Rejseplan\Response\ArrivalBoardResponse;

class ArrivalBoard extends AbstractBoard
{
    public function request(): ArrivalBoardResponse
    {
        return $this->makeRequest();
    }

    protected function getUrl(): string
    {
        return 'arrivalBoard';
    }

    protected function makeResponse(string $response): string
    {
        $json = json_decode($response, true, flags: JSON_THROW_ON_ERROR)['ArrivalBoard']['Arrival'];
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

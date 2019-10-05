<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest;

use Lsv\Rejseplan\DepartureBoard;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class DepartureBoardTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_multiple_departures(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/departureboard.json')),
        ]);

        $board = new DepartureBoard($client);
        $response = $board->request('123');

        $this->assertCount(20, $response->getDepartures());
    }

    /**
     * @test
     */
    public function can_get_single_depature(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/departureboard_single.json')),
        ]);

        $board = new DepartureBoard($client);
        $response = $board->request('123');

        $this->assertCount(1, $response->getDepartures());

        $this->assertSame('Bus 2A', $response->departures[0]->name);
        $this->assertSame('BUS', $response->departures[0]->type);
        $this->assertSame('Hovedbanegården, Tivoli (Bernstorffsgade)', $response->departures[0]->stop);
        $this->assertSame('2016-09-09 14:49', $response->departures[0]->scheduledDate->format('Y-m-d H:i'));
        $this->assertFalse($response->departures[0]->hasMessages);
        $this->assertNull($response->departures[0]->scheduledTrack);
        $this->assertSame('2016-09-09 15:06', $response->departures[0]->realtimeDate->format('Y-m-d H:i'));
        $this->assertNull($response->departures[0]->realtimeTrack);
        $this->assertSame('Tingbjerg, Gavlhusvej (Terrasserne)', $response->departures[0]->getFinalStop());
        $this->assertSame('Tingbjerg Gavlhusvej', $response->departures[0]->getDirection());
        $this->assertSame('http://baseurl/journeyDetail?ref=85713%2F32015%2F11902%2F22621%2F86%3Fdate%3D09.09.16%26format%3Djson%26', $response->departures[0]->journeyDetails);
    }
}

<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest;

use DateTime;
use Lsv\Rejseplan\ArrivalBoard;
use Lsv\Rejseplan\Response\Location\Stop;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ArrivalBoardTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_multiple_arrivals(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/arrivalboard.json')),
        ]);

        $board = new ArrivalBoard($client);
        $response = $board->request('123');

        $this->assertCount(20, $response->arrivals);
    }

    /**
     * @test
     */
    public function can_get_single_arrival(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/arrivalboard_single.json')),
        ]);

        $board = new ArrivalBoard($client);
        $response = $board->request('123');

        $this->assertCount(1, $response->getArrivals());

        $this->assertSame('RE 1065', $response->arrivals[0]->getName());
        $this->assertSame('TOG', $response->arrivals[0]->getType());
        $this->assertSame('København H', $response->arrivals[0]->getStop());
        $this->assertSame('2016-09-09 14:48', $response->arrivals[0]->getScheduledDate()->format('Y-m-d H:i'));
        $this->assertTrue($response->arrivals[0]->hasMessages());
        $this->assertSame('1', $response->arrivals[0]->getScheduledTrack());
        $this->assertSame('2016-09-09 14:59', $response->arrivals[0]->getRealtimeDate()->format('Y-m-d H:i'));
        $this->assertSame('1', $response->arrivals[0]->getRealtimeTrack());
        $this->assertSame('Kalmar C', $response->arrivals[0]->getOrigin());
        $this->assertSame('http://baseurl/journeyDetail?ref=3849%2F31310%2F372456%2F184946%2F86%3Fdate%3D09.09.16%26format%3Djson%26', $response->arrivals[0]->getJourney());
    }

    /**
     * @test
     */
    public function can_set_url_parameters(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/arrivalboard_single.json')),
        ]);

        $board = new ArrivalBoard($client);
        $board
            ->setDate(new DateTime('2019-05-23 14:22'))
            ->setDontUseBus()
            ->setDontUseMetro()
            ->setDontUseTrain();

        $board->request('123');
        $query = $board->getQuery();

        $this->assertFalse($query['useTog']);
        $this->assertFalse($query['useBus']);
        $this->assertFalse($query['useMetro']);
        $this->assertSame('23.05.19', $query['date']);
        $this->assertSame('14:22', $query['time']);
    }

    /**
     * @test
     */
    public function can_get_board_via_stop(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/arrivalboard_single.json')),
        ]);

        $location = new Stop();
        $location->id = '00012992';

        $board = new ArrivalBoard($client);
        $board->request($location);
        $query = $board->getQuery();
        $this->assertSame('00012992', $query['id']);
    }
}

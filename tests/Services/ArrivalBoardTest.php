<?php

namespace RejseplanApiTest\Services;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use RejseplanApi\Services\ArrivalBoard;
use RejseplanApi\Services\Response\LocationResponse;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class ArrivalBoardTest extends AbstractServicesTest
{
    public function test_get_response_without_call(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/arrivalboard_single.json'));
        $board = new ArrivalBoard($this->getBaseUrl(), $client);
        $board->setLocation($this->getLocationResponse());
        $response = $board->getResponse();
        $this->assertStringEqualsFile(__DIR__ . '/mocks/arrivalboard_single.json', $response->getBody());
    }

    public function test_url_setLocation(): void
    {
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse());
        $uri = $board->getRequest()->getUri();
        $this->assertEquals('/arrivalBoard', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals($this->getLocationResponse()->getId(), $query['id']);
    }

    public function test_url_setStop(): void
    {
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation($this->getStopLocationResponse());
        $uri = $board->getRequest()->getUri();
        $this->assertEquals('/arrivalBoard', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals($this->getStopLocationResponse()->getId(), $query['id']);
    }

    public function test_url_setlocation_string(): void
    {
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation('004856632');
        $uri = $board->getRequest()->getUri();
        $this->assertEquals('/arrivalBoard', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('004856632', $query['id']);
    }

    public function test_url_setlocation_int(): void
    {
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation(104856632);
        $uri = $board->getRequest()->getUri();
        $this->assertEquals('/arrivalBoard', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals(104856632, $query['id']);
    }

    public function test_url_setLocation_not_a_stop(): void
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'The location must be a station');
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
    }

    public function test_url_setLocation_invalid(): void
    {
        $this->setExpectedException(
            \InvalidArgumentException::class,
            'The location must be either a LocationResponse object, StopLocationResponse object, string or integer'
        );
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation($this->getCoordinate());
    }

    public function test_url_setDontUseTrain(): void
    {
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse());
        $board->setDontUseTrain();
        $uri = $board->getRequest()->getUri();
        parse_str($uri->getQuery(), $query);
        $this->assertEquals(0, $query['useTog']);
    }

    public function test_url_setDontUseBus(): void
    {
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse());
        $board->setDontUseBus();
        $uri = $board->getRequest()->getUri();
        parse_str($uri->getQuery(), $query);
        $this->assertEquals(0, $query['useBus']);
    }

    public function test_url_setDontUseMetro(): void
    {
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse());
        $board->setDontUseMetro();
        $uri = $board->getRequest()->getUri();
        parse_str($uri->getQuery(), $query);
        $this->assertEquals(0, $query['useMetro']);
    }

    public function test_url_setDate(): void
    {
        $date = date_create_from_format('Y-m-d H:i', '2016-11-26 12:35');

        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse());
        $board->setDate($date);
        $uri = $board->getRequest()->getUri();
        parse_str($uri->getQuery(), $query);
        $this->assertEquals('26.11.16', $query['date']);
        $this->assertEquals('12:35', $query['time']);
    }

    public function test_not_configured_correct(): void
    {
        $this->setExpectedException(MissingOptionsException::class, 'The required option "id" is missing.');
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->call();
    }

    public function test_single(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/arrivalboard_single.json'));
        $board = new ArrivalBoard($this->getBaseUrl(), $client);
        $board->setLocation($this->getLocationResponse());
        $response = $board->call();

        $lastDate = date_create_from_format('d.m.y H:i', '09.09.16 14:59');
        $this->assertEquals($lastDate->format('Y-m-d H:i'), $response->getNextBoardDate()->format('Y-m-d H:i'));
        $this->assertCount(1, $response->getArrivals());

        $departure = $response->getArrivals()[0];
        $this->assertEquals('RE 1065', $departure->getName());
        $this->assertEquals('TOG', $departure->getType());
        $this->assertEquals('København H', $departure->getStop());
        $this->assertEquals('2016-09-09 14:48', $departure->getScheduledDate()->format('Y-m-d H:i'), 'scheduled');
        $this->assertEquals('2016-09-09 14:59', $departure->getRealDate()->format('Y-m-d H:i'), 'real time');
        $this->assertTrue($departure->isDelayed());
        $this->assertTrue($departure->hasMessages());
        $this->assertEquals('Kalmar C', $departure->getOrigin());
    }

    public function test_response(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/arrivalboard.json'));
        $board = new ArrivalBoard($this->getBaseUrl(), $client);
        $board->setLocation($this->getLocationResponse());
        $response = $board->call();

        $lastDate = date_create_from_format('d.m.y H:i', '09.09.16 15:04');
        $this->assertEquals($lastDate->format('Y-m-d H:i'), $response->getNextBoardDate()->format('Y-m-d H:i'));
        $this->assertCount(20, $response->getArrivals());

        $departure = $response->getArrivals()[18];
        $this->assertEquals('Bus 6A', $departure->getName());
        $this->assertEquals('BUS', $departure->getType());
        $this->assertEquals('Hovedbanegården (Vesterbrogade)', $departure->getStop());
        $this->assertEquals('2016-09-09 14:59', $departure->getScheduledDate()->format('Y-m-d H:i'));
        $this->assertEquals('2016-09-09 15:00', $departure->getRealDate()->format('Y-m-d H:i'));
        $this->assertTrue($departure->isDelayed());
        $this->assertNull($departure->getScheduledTrack());
        $this->assertNull($departure->getRealTrack());
        $this->assertFalse($departure->hasMessages());
        $this->assertEquals('Emdrup Torv (Emdrupvej)', $departure->getOrigin());

        $departure = $response->getArrivals()[17];
        $this->assertEquals('B', $departure->getName());
        $this->assertEquals('S', $departure->getType());
        $this->assertEquals('København H', $departure->getStop());
        $this->assertEquals('2016-09-09 14:59', $departure->getScheduledDate()->format('Y-m-d H:i'));
        $this->assertFalse($departure->isDelayed());
        $this->assertEquals('11-12', $departure->getScheduledTrack());
        $this->assertEquals('11-12', $departure->getRealTrack());
        $this->assertFalse($departure->isTrackChanged());
        $this->assertFalse($departure->hasMessages());
        $this->assertEquals('Farum St.', $departure->getOrigin());
    }

    public function test_getResponse(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/arrivalboard.json'));
        $board = new ArrivalBoard($this->getBaseUrl(), $client);
        $board->setLocation($this->getLocationResponse());
        $response = $board->call();
        $this->assertCount(20, $response->getArrivals());
    }

    public function test_no_board(): void
    {
        $mock = str_replace('_KEY_', 'ArrivalBoard', file_get_contents(__DIR__ . '/mocks/board_error.json'));
        $client = $this->getClientWithMock($mock);
        $board = new ArrivalBoard($this->getBaseUrl(), $client);
        $board->setLocation($this->getLocationResponse());
        $response = $board->call();
        $this->assertCount(0, $response->getArrivals());
    }

    public function test_error(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/error.txt'));
        $board = new ArrivalBoard($this->getBaseUrl(), $client);
        $board->setLocation($this->getLocationResponse());
        $response = $board->call();
        $this->assertCount(0, $response->getArrivals());
    }

    public function test_guzzle_error(): void
    {
        $this->expectException(GuzzleException::class);

        $handler = new MockHandler([
            new Response(500),
        ]);
        $client = $this->getClient($handler);
        $board = new ArrivalBoard($this->getBaseUrl(), $client);
        $board->setLocation($this->getLocationResponse());
        $board->call();
    }
}

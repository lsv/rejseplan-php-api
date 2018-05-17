<?php

namespace RejseplanApiTest\Services;

use RejseplanApi\Services\DepartureBoard;
use RejseplanApi\Services\Response\LocationResponse;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class DepartureBoardTest extends AbstractServicesTest
{
    public function test_url_setLocation(): void
    {
        $board = new DepartureBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse());
        $uri = $board->getRequest()->getUri();
        $this->assertEquals('/departureBoard', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals($this->getLocationResponse()->getId(), $query['id']);
    }

    public function test_url_setStop(): void
    {
        $board = new DepartureBoard($this->getBaseUrl());
        $board->setLocation($this->getStopLocationResponse());
        $uri = $board->getRequest()->getUri();
        $this->assertEquals('/departureBoard', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals($this->getStopLocationResponse()->getId(), $query['id']);
    }

    public function test_url_setlocation_string(): void
    {
        $board = new DepartureBoard($this->getBaseUrl());
        $board->setLocation('004856632');
        $uri = $board->getRequest()->getUri();
        $this->assertEquals('/departureBoard', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('004856632', $query['id']);
    }

    public function test_url_setlocation_int(): void
    {
        $board = new DepartureBoard($this->getBaseUrl());
        $board->setLocation(104856632);
        $uri = $board->getRequest()->getUri();
        $this->assertEquals('/departureBoard', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals(104856632, $query['id']);
    }

    public function test_url_setLocation_not_a_stop(): void
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'The location must be a station');
        $board = new DepartureBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
    }

    public function test_url_setLocation_invalid(): void
    {
        $this->setExpectedException(
            \InvalidArgumentException::class,
            'The location must be either a LocationResponse object, StopLocationResponse object, string or integer'
        );
        $board = new DepartureBoard($this->getBaseUrl());
        $board->setLocation($this->getCoordinate());
    }

    public function test_url_setDontUseTrain(): void
    {
        $board = new DepartureBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse());
        $board->setDontUseTrain();
        $uri = $board->getRequest()->getUri();
        parse_str($uri->getQuery(), $query);
        $this->assertEquals(0, $query['useTog']);
    }

    public function test_url_setDontUseBus(): void
    {
        $board = new DepartureBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse());
        $board->setDontUseBus();
        $uri = $board->getRequest()->getUri();
        parse_str($uri->getQuery(), $query);
        $this->assertEquals(0, $query['useBus']);
    }

    public function test_url_setDontUseMetro(): void
    {
        $board = new DepartureBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse());
        $board->setDontUseMetro();
        $uri = $board->getRequest()->getUri();
        parse_str($uri->getQuery(), $query);
        $this->assertEquals(0, $query['useMetro']);
    }

    public function test_url_setDate(): void
    {
        $date = date_create_from_format('Y-m-d H:i', '2016-11-26 12:35');

        $board = new DepartureBoard($this->getBaseUrl());
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
        $board = new DepartureBoard($this->getBaseUrl());
        $board->call();
    }

    public function test_single(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/departureboard_single.json'));
        $board = new DepartureBoard($this->getBaseUrl(), $client);
        $board->setLocation($this->getLocationResponse());
        $response = $board->call();

        $lastDate = date_create_from_format('d.m.y H:i', '09.09.16 15:06');
        $this->assertEquals($lastDate->format('Y-m-d H:i'), $response->getNextBoardDate()->format('Y-m-d H:i'));
        $this->assertCount(1, $response->getDepartures());

        $departure = $response->getDepartures()[0];
        $this->assertEquals('Bus 2A', $departure->getName());
        $this->assertEquals('BUS', $departure->getType());
        $this->assertEquals('Hovedbanegården, Tivoli (Bernstorffsgade)', $departure->getStop());
        $this->assertEquals('2016-09-09 14:49', $departure->getScheduledDate()->format('Y-m-d H:i'));
        $this->assertEquals('2016-09-09 15:06', $departure->getRealDate()->format('Y-m-d H:i'));
        $this->assertTrue($departure->isDelayed());
        $this->assertNull($departure->getScheduledTrack());
        $this->assertNull($departure->getRealTrack());
        $this->assertFalse($departure->hasMessages());
        $this->assertEquals('Tingbjerg, Gavlhusvej (Terrasserne)', $departure->getFinalStop());
        $this->assertEquals('Tingbjerg Gavlhusvej', $departure->getDirection());
        $this->assertEquals('https://baseurl/journeyDetail?ref=85713%2F32015%2F11902%2F22621%2F86%3Fdate%3D09.09.16%26format%3Djson%26', $departure->getJourneyDetails());
    }

    public function test_response(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/departureboard.json'));
        $board = new DepartureBoard($this->getBaseUrl(), $client);
        $board->setLocation($this->getLocationResponse());
        $response = $board->call();

        $lastDate = date_create_from_format('d.m.y H:i', '09.09.16 14:59');
        $this->assertEquals($lastDate->format('Y-m-d H:i'), $response->getNextBoardDate()->format('Y-m-d H:i'));
        $this->assertCount(20, $response->getDepartures());

        $departure = $response->getDepartures()[18];
        $this->assertEquals('Bus 5A', $departure->getName());
        $this->assertEquals('BUS', $departure->getType());
        $this->assertEquals('Hovedbanegården, Tivoli (Bernstorffsgade)', $departure->getStop());
        $this->assertEquals('2016-09-09 14:58', $departure->getScheduledDate()->format('Y-m-d H:i'));
        $this->assertEquals('2016-09-09 15:00', $departure->getRealDate()->format('Y-m-d H:i'));
        $this->assertTrue($departure->isDelayed());
        $this->assertNull($departure->getScheduledTrack());
        $this->assertNull($departure->getRealTrack());
        $this->assertFalse($departure->hasMessages());
        $this->assertEquals('Husum Torv (Sløjfen)', $departure->getFinalStop());
        $this->assertEquals('Husum Torv', $departure->getDirection());
        $this->assertEquals('https://baseurl/journeyDetail?ref=432150%2F149423%2F342368%2F27134%2F86%3Fdate%3D09.09.16%26format%3Djson%26', $departure->getJourneyDetails());
        $this->assertFalse($departure->usesTrack());

        $departure = $response->getDepartures()[13];
        $this->assertEquals('Re 4557', $departure->getName());
        $this->assertEquals('REG', $departure->getType());
        $this->assertEquals('København H', $departure->getStop());
        $this->assertEquals('2016-09-09 14:58', $departure->getScheduledDate()->format('Y-m-d H:i'));
        $this->assertEquals('2016-09-09 14:58', $departure->getRealDate()->format('Y-m-d H:i'));
        $this->assertFalse($departure->isDelayed());
        $this->assertEquals('8', $departure->getScheduledTrack());
        $this->assertEquals('7', $departure->getRealTrack());
        $this->assertTrue($departure->isTrackChanged());
        $this->assertTrue($departure->hasMessages());
        $this->assertTrue($departure->usesTrack());
        $this->assertEquals('Holbæk St.', $departure->getFinalStop());
        $this->assertEquals('Holbæk St.', $departure->getDirection());
        $this->assertEquals('https://baseurl/journeyDetail?ref=297366%2F107304%2F429762%2F115768%2F86%3Fdate%3D09.09.16%26format%3Djson%26', $departure->getJourneyDetails());
    }

    public function test_no_board(): void
    {
        $mock = str_replace('_KEY_', 'DepartureBoard', file_get_contents(__DIR__ . '/mocks/board_error.json'));
        $client = $this->getClientWithMock($mock);
        $board = new DepartureBoard($this->getBaseUrl(), $client);
        $board->setLocation($this->getLocationResponse());
        $response = $board->call();
        $this->assertCount(0, $response->getDepartures());
    }

    public function test_error(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/error.txt'));
        $board = new DepartureBoard($this->getBaseUrl(), $client);
        $board->setLocation($this->getLocationResponse());
        $response = $board->call();
        $this->assertCount(0, $response->getDepartures());
    }
}

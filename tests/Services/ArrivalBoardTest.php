<?php
namespace RejseplanApiTest\Services;

use Psr\Http\Message\ResponseInterface;
use RejseplanApi\Services\ArrivalBoard;
use RejseplanApi\Services\Response\ArrivalBoardResponse;
use RejseplanApi\Services\Response\LocationResponse;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class ArrivalBoardTest extends AbstractServicesTest
{

    public function test_url_setLocation()
    {
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse());
        $uri = $board->getRequest()->getUri();
        $this->assertEquals('/arrivalBoard', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals($this->getLocationResponse()->getId(), $query['id']);
    }

    public function test_url_setLocation_not_a_stop()
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'The location must be a station');
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
    }

    public function test_url_setDontUseTrain()
    {
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse());
        $board->setDontUseTrain();
        $uri = $board->getRequest()->getUri();
        parse_str($uri->getQuery(), $query);
        $this->assertEquals(0, $query['useTog']);
    }

    public function test_url_setDontUseBus()
    {
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse());
        $board->setDontUseBus();
        $uri = $board->getRequest()->getUri();
        parse_str($uri->getQuery(), $query);
        $this->assertEquals(0, $query['useBus']);
    }

    public function test_url_setDontUseMetro()
    {
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->setLocation($this->getLocationResponse());
        $board->setDontUseMetro();
        $uri = $board->getRequest()->getUri();
        parse_str($uri->getQuery(), $query);
        $this->assertEquals(0, $query['useMetro']);
    }

    public function test_url_setDate()
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

    public function test_not_configured_correct()
    {
        $this->setExpectedException(MissingOptionsException::class, 'The required option "id" is missing.');
        $board = new ArrivalBoard($this->getBaseUrl());
        $board->call();
    }

    public function test_response()
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/arrivalboard.json'));
        $board = new ArrivalBoard($this->getBaseUrl(), $client);
        $board->setLocation($this->getLocationResponse());
        $response = $board->call();

        $this->assertInstanceOf(ArrivalBoardResponse::class, $response);

        $lastDate = date_create_from_format('d.m.y H:i', '09.09.16 14:59');
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
        $this->assertEquals('0', $departure->getMessages());
        $this->assertEquals('Emdrup Torv (Emdrupvej)', $departure->getOrigin());
        $this->assertEquals('http://baseurl/journeyDetail?ref=467937%2F161774%2F740016%2F214030%2F86%3Fdate%3D09.09.16%26format%3Djson%26', $departure->getJourneyDetails());

        $departure = $response->getArrivals()[17];
        $this->assertEquals('B', $departure->getName());
        $this->assertEquals('S', $departure->getType());
        $this->assertEquals('København H', $departure->getStop());
        $this->assertEquals('2016-09-09 14:59', $departure->getScheduledDate()->format('Y-m-d H:i'));
        $this->assertFalse($departure->isDelayed());
        $this->assertEquals('11-12', $departure->getScheduledTrack());
        $this->assertEquals('11-12', $departure->getRealTrack());
        $this->assertFalse($departure->isTrackChanged());
        $this->assertEquals('0', $departure->getMessages());
        $this->assertEquals('Farum St.', $departure->getOrigin());
        $this->assertEquals('http://baseurl/journeyDetail?ref=444684%2F168160%2F352668%2F28137%2F86%3Fdate%3D09.09.16%26format%3Djson%26', $departure->getJourneyDetails());

    }

    public function test_getResponse()
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/arrivalboard.json'));
        $board = new ArrivalBoard($this->getBaseUrl(), $client);
        $board->setLocation($this->getLocationResponse());
        $this->assertInstanceOf(ResponseInterface::class, $board->getResponse());
    }

}

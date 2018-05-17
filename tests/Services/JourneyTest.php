<?php

namespace RejseplanApiTest\Services;

use RejseplanApi\Services\Journey;
use RejseplanApi\Services\Response\StationBoard\BoardData;
use RejseplanApi\Services\Response\Trip\Leg;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class JourneyTest extends AbstractServicesTest
{
    public function test_url_setUrl(): void
    {
        $journey = new Journey($this->getBaseUrl());
        $journey->setUrl('http://baseurl/journey?foo=bar');
        $uri = $journey->getRequest()->getUri();
        $this->assertEquals('/journey', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);

        $journey = new Journey($this->getBaseUrl());
        $journey->setUrl('http://baseurl/journey?foo=bar&format=json');
        $uri = $journey->getRequest()->getUri();
        $this->assertEquals('/journey', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
    }

    public function test_url_setUrlFromLeg(): void
    {
        $url = 'http://baseurl/journeyDetail';
        $query = 'ref=444684%2F168160%2F352668%2F28137%2F86%3Fdate%3D09.09.16%26format%3Djson%26';

        $stub = $this->getMockBuilder(Leg::class)->getMock();
        $stub
            ->method('getJournyDetails')
            ->willReturn(sprintf('%s?%s', $url, $query))
        ;

        $journey = new Journey($this->getBaseUrl());
        $journey->setUrl($stub);
        $uri = $journey->getRequest()->getUri();
        $this->assertEquals('/journeyDetail', $uri->getPath());
        $this->assertEquals(
            $query . '&format=json',
            $uri->getQuery()
        );
    }

    public function test_url_setUrlFromBoardData(): void
    {
        $url = 'http://baseurl/journeyDetail';
        $query = 'ref=444684%2F168160%2F352668%2F28137%2F86%3Fdate%3D09.09.16%26format%3Djson%26';

        $stub = $this->getMockBuilder(BoardData::class)->getMock();
        $stub
            ->method('getJourneyDetails')
            ->willReturn(sprintf('%s?%s', $url, $query))
        ;

        $journey = new Journey($this->getBaseUrl());
        $journey->setUrl($stub);
        $uri = $journey->getRequest()->getUri();
        $this->assertEquals('/journeyDetail', $uri->getPath());
        $this->assertEquals(
            $query . '&format=json',
            $uri->getQuery()
        );
    }

    public function test_setUrl_invalid(): void
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'setUrl must be a string, Leg or BoardData object');
        $journey = new Journey($this->getBaseUrl());
        $journey->setUrl($this->getCoordinate());
    }

    public function test_not_configured_correct(): void
    {
        $this->setExpectedException(MissingOptionsException::class, 'The required option "url" is missing.');
        $journey = new Journey($this->getBaseUrl());
        $journey->call();
    }

    public function test_response(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/journey.json'));
        $journey = new Journey($this->getBaseUrl(), $client);
        $journey->setUrl('xxx');
        $response = $journey->call();

        $this->assertEquals('Re 4557', $response->getName());
        $this->assertEquals('REG', $response->getType());
        $this->assertCount(14, $response->getStops());
        $this->assertCount(2, $response->getMessages());
        $this->assertCount(3, $response->getNotes());

        $stop = $response->getStops()[8];

        $this->assertEquals('Roskilde St.', $stop->getName());
        $this->assertEquals('12.088550', $stop->getCoordinate()->getLatitude());
        $this->assertEquals('55.639093', $stop->getCoordinate()->getLongitude());
        $this->assertEquals('8', $stop->getIndex());
        $this->assertEquals('2016-09-09 15:24', $stop->getScheduledDeparture()->format('Y-m-d H:i'));
        $this->assertEquals('2016-09-09 15:23', $stop->getScheduledArrival()->format('Y-m-d H:i'));
        $this->assertEquals('1', $stop->getScheduledTrack());
        $this->assertEquals('2016-09-09 15:29', $stop->getRealtimeDeparture()->format('Y-m-d H:i'));
        $this->assertEquals('2016-09-09 15:30', $stop->getRealtimeArrival()->format('Y-m-d H:i'));
        $this->assertEquals('2', $stop->getRealtimeTrack());
        $this->assertTrue($stop->isTrackChanged());
        $this->assertTrue($stop->isDepartureDelay());
        $this->assertTrue($stop->isArrivalDelay());
        $this->assertTrue($stop->usesTrack());

        $message = $response->getMessages()[1];
        $this->assertEquals('Stoppestedsflytning Astrupvej', $message->getHeader());
        $this->assertEquals("Stoppestedet Astrupvej for bus linje 2A, 5A og 81N mod København flyttes ca. 100 meter frem.\nGyldigt fra 15-08-2016 kl 8:00 til ca. 15-11-2016 kl 16:00.\nDet skyldes Frederikssundsvejsprojektet.", $message->getText());
    }

    public function test_small_response(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/journey_small.json'));
        $journey = new Journey($this->getBaseUrl(), $client);
        $journey->setUrl('xxx');
        $response = $journey->call();

        $this->assertEquals('Traktorbus', $response->getName());
        $this->assertEquals('BUS', $response->getType());
        $this->assertCount(2, $response->getStops());
        $this->assertCount(0, $response->getMessages());
        $this->assertCount(2, $response->getNotes());
    }

    public function test_messages(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/journey_single_message.json'));
        $journey = new Journey($this->getBaseUrl(), $client);
        $journey->setUrl('xxx');
        $response = $journey->call();
        $this->assertCount(1, $response->getMessages());
    }

    public function test_message(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/journey_short_message.json'));
        $journey = new Journey($this->getBaseUrl(), $client);
        $journey->setUrl('xxx');
        $response = $journey->call();
        $this->assertCount(1, $response->getMessages());
    }

    public function test_single_stop(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/journey_single_stop.json'));
        $journey = new Journey($this->getBaseUrl(), $client);
        $journey->setUrl('xxx');
        $response = $journey->call();
        $this->assertCount(1, $response->getStops());
        $this->assertFalse($response->getStops()[0]->isArrivalDelay());
        $this->assertFalse($response->getStops()[0]->isDepartureDelay());
        $this->assertFalse($response->getStops()[0]->isTrackChanged());
        $this->assertFalse($response->getStops()[0]->usesTrack());
    }

    public function test_errored(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/error.txt'));
        $journey = new Journey($this->getBaseUrl(), $client);
        $journey->setUrl('xxx');
        $response = $journey->call();
        $this->assertNull($response);
    }
}

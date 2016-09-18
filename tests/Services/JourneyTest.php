<?php
namespace RejseplanApiTest\Services;

use RejseplanApi\Services\Journey;
use RejseplanApi\Services\Response\JourneyResponse;
use RejseplanApi\Services\Response\StationBoard\BoardData;
use RejseplanApi\Services\Response\Trip\Leg;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class JourneyTest extends AbstractServicesTest
{

    public function test_url_setUrl()
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

    public function test_url_setUrlFromLeg()
    {
        $url = 'http://baseurl/journeyDetail';
        $query = 'ref=444684%2F168160%2F352668%2F28137%2F86%3Fdate%3D09.09.16%26format%3Djson%26';

        $stub = $this->createMock(Leg::class);
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

    public function test_url_setUrlFromBoardData()
    {
        $url = 'http://baseurl/journeyDetail';
        $query = 'ref=444684%2F168160%2F352668%2F28137%2F86%3Fdate%3D09.09.16%26format%3Djson%26';

        $stub = $this->createMock(BoardData::class);
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

    public function test_setUrl_invalid()
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'setUrl must be a string, Leg or BoardData object');
        $journey = new Journey($this->getBaseUrl());
        $journey->setUrl($this->getCoordinate());
    }

    public function test_not_configured_correct()
    {
        $this->setExpectedException(MissingOptionsException::class, 'The required option "url" is missing.');
        $journey = new Journey($this->getBaseUrl());
        $journey->call();
    }

    public function test_response()
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/journey.json'));
        $journey = new Journey($this->getBaseUrl(), $client);
        $journey->setUrl('xxx');
        $response = $journey->call();
        $this->assertInstanceOf(JourneyResponse::class, $response);

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

        $message = $response->getMessages()[1];
        $this->assertEquals('Stoppestedsflytning Astrupvej', $message->getHeader());
        $this->assertEquals("Stoppestedet Astrupvej for bus linje 2A, 5A og 81N mod København flyttes ca. 100 meter frem.\nGyldigt fra 15-08-2016 kl 8:00 til ca. 15-11-2016 kl 16:00.\nDet skyldes Frederikssundsvejsprojektet.", $message->getText());

    }

    public function test_small_response()
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/journey_small.json'));
        $journey = new Journey($this->getBaseUrl(), $client);
        $journey->setUrl('xxx');
        $response = $journey->call();
        $this->assertInstanceOf(JourneyResponse::class, $response);

        $this->assertEquals('Traktorbus', $response->getName());
        $this->assertEquals('BUS', $response->getType());
        $this->assertCount(2, $response->getStops());
        $this->assertCount(0, $response->getMessages());
        $this->assertCount(2, $response->getNotes());

    }

    public function test_messages()
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__.'/mocks/journey_single_message.json'));
        $journey = new Journey($this->getBaseUrl(), $client);
        $journey->setUrl('xxx');
        $response = $journey->call();
        $this->assertCount(1, $response->getMessages());
    }

    public function test_single_stop()
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__.'/mocks/journey_single_stop.json'));
        $journey = new Journey($this->getBaseUrl(), $client);
        $journey->setUrl('xxx');
        $response = $journey->call();
        $this->assertCount(1, $response->getStops());
    }

}

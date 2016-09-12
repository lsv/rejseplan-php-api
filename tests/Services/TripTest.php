<?php
namespace RejseplanApiTest\Services;

use RejseplanApi\Services\Response\LocationResponse;
use RejseplanApi\Services\Response\TripResponse;
use RejseplanApi\Services\Trip;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class TripTest extends AbstractServicesTest
{

    /**
     * @var TripResponse[]
     */
    private $response;

    public function setUp()
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/trip.json'));
        $location = new Trip($this->getBaseUrl(), $client);
        $location->setOrigin($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
        $location->setDestination($this->getStopLocationResponse());
        $this->response = $location->call();
    }


    public function test_url_setOriginAndDestination()
    {
        $location = new Trip($this->getBaseUrl());
        $location->setOrigin($this->getLocationResponse());
        $location->setDestination($this->getStopLocationResponse());

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/trip', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals($this->getLocationResponse()->getId(), $query['originId']);
        $this->assertEquals($this->getStopLocationResponse()->getId(), $query['destId']);
    }

    public function test_url_setViaLocation()
    {
        $location = new Trip($this->getBaseUrl());
        $location->setOrigin($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
        $location->setDestination($this->getStopLocationResponse());
        $location->setVia($this->getLocationResponse());

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/trip', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals($this->getLocationResponse()->getName(), $query['originCoordName']);
        $this->assertEquals($this->getLocationResponse()->getCoordinate()->getXCoordinate(), $query['originCoordX']);
        $this->assertEquals($this->getLocationResponse()->getCoordinate()->getYCoordinate(), $query['originCoordY']);
        $this->assertEquals($this->getStopLocationResponse()->getId(), $query['destId']);
        $this->assertEquals($this->getLocationResponse()->getId(), $query['via']);
    }

    public function test_url_setViaStop()
    {
        $location = new Trip($this->getBaseUrl());
        $location->setOrigin($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
        $location->setDestination($this->getStopLocationResponse());
        $location->setVia($this->getStopLocationResponse());

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/trip', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals($this->getLocationResponse()->getName(), $query['originCoordName']);
        $this->assertEquals($this->getLocationResponse()->getCoordinate()->getXCoordinate(), $query['originCoordX']);
        $this->assertEquals($this->getLocationResponse()->getCoordinate()->getYCoordinate(), $query['originCoordY']);
        $this->assertEquals($this->getStopLocationResponse()->getId(), $query['destId']);
        $this->assertEquals($this->getStopLocationResponse()->getId(), $query['via']);
    }

    public function test_url_setViaException()
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'Via is not a stop location');
        $location = new Trip($this->getBaseUrl());
        $location->setOrigin($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
        $location->setDestination($this->getStopLocationResponse());
        $location->setVia($this->getLocationResponse(LocationResponse::LOCATIONTYPE_POI));
        $location->getRequest();
    }

    public function test_url_setDate()
    {
        $date = date_create_from_format('Y-m-d H:i', '2016-05-23 12:55');

        $location = new Trip($this->getBaseUrl());
        $location->setOrigin($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
        $location->setDestination($this->getStopLocationResponse());
        $location->setDate($date);

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/trip', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals('23.05.16', $query['date']);
        $this->assertEquals('12:55', $query['time']);
    }

    public function test_url_setDontUseBus()
    {
        $location = new Trip($this->getBaseUrl());
        $location->setOrigin($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
        $location->setDestination($this->getStopLocationResponse());
        $location->setDontUseBus();

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/trip', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals('0', $query['useBus']);
    }

    public function test_url_setDontUseTrain()
    {
        $location = new Trip($this->getBaseUrl());
        $location->setOrigin($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
        $location->setDestination($this->getStopLocationResponse());
        $location->setDontUseTrain();

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/trip', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals('0', $query['useTog']);
    }

    public function test_url_setDontUseMetro()
    {
        $location = new Trip($this->getBaseUrl());
        $location->setOrigin($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
        $location->setDestination($this->getStopLocationResponse());
        $location->setDontUseMetro();

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/trip', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals('0', $query['useMetro']);
    }

    public function test_url_setWalkingDistance()
    {
        $location = new Trip($this->getBaseUrl());
        $location->setOrigin($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
        $location->setDestination($this->getStopLocationResponse());
        $location->setWalkingDistance(500, 3000);

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/trip', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals('500', $query['maxWalkingDistanceDep']);
        $this->assertEquals('3000', $query['maxWalkingDistanceDest']);
    }

    public function test_url_setUseBicycle()
    {
        $location = new Trip($this->getBaseUrl());
        $location->setOrigin($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
        $location->setDestination($this->getStopLocationResponse());
        $location->setUseBicycle(500, 3000);

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/trip', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals('500', $query['maxCyclingDistanceDep']);
        $this->assertEquals('3000', $query['maxCyclingDistanceDest']);
        $this->assertEquals('1', $query['useBicycle']);
    }

    public function test_url_DistanceOverMaxValue()
    {
        $this->setExpectedException(InvalidOptionsException::class, 'The option "maxCyclingDistanceDep" with value 100 is invalid.');
        $location = new Trip($this->getBaseUrl());
        $location->setOrigin($this->getLocationResponse(LocationResponse::LOCATIONTYPE_ADDRESS));
        $location->setDestination($this->getStopLocationResponse());
        $location->setUseBicycle(100, 25000);

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/trip', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals('500', $query['maxCyclingDistanceDep']);
        $this->assertEquals('3000', $query['maxCyclingDistanceDest']);
        $this->assertEquals('1', $query['useBicycle']);
    }

    public function test_not_configured_correct()
    {
        $this->setExpectedException(MissingOptionsException::class, 'The required options "dest", "origin" are missing.');
        $location = new Trip($this->getBaseUrl());
        $location->call();
    }

    public function dataProvider()
    {
        $key0Leg0 = [
            0,
            3,
            [
                0,
                'Cykel',
                'BIKE',
                ['Tivoli Hotel (Kalvebod Brygge)', 'ST', null, '15:03', '09.09.16', null, null],
                ['Dybbølsbro St.', 'ST', null, '15:06', '09.09.16', null, null],
                ['Varighed: 3 min.', '(Afstand: ca. 0,6 km)'],
                null,
                '09.09.16',
                '15:03',
                '09.09.16',
                '16:06'
            ]
        ];

        $key0Leg3 = [
            0,
            3,
            [
                2,
                'Re 4359',
                'REG',
                ['Ny Ellebjerg St.', 'ST', 3, '15:21', '09.09.16', '24', '24'],
                ['Ringsted St.', 'ST', 11, '16:06', '09.09.16', 1, 1],
                ['Retning: Slagelse St.', 'Mulighed for internet'],
                'http://baseurl/journeyDetail?ref=525795%2F183209%2F378240%2F13860%2F86%3Fdate%3D09.09.16%26station_evaId%3D8600783%26format%3Djson%26',
                '09.09.16',
                '15:03',
                '09.09.16',
                '16:06'
            ]
        ];

        $key1Leg2 = [
            1,
            3,
            [
                2,
                'IC 861',
                'IC',
                ['Valby St.', 'ST', 3, '15:37', '09.09.16', 2, 3],
                ['Ringsted St.', 'ST', 11, '16:10', '09.09.16', 1, 1],
                ['Retning: Esbjerg St.', 'Reservering anbefales', 'Mulighed for internet', 'Børneguide'],
                'http://baseurl/journeyDetail?ref=890592%2F304185%2F778536%2F92404%2F86%3Fdate%3D09.09.16%26station_evaId%3D8600624%26format%3Djson%26',
                '09.09.16',
                '15:25',
                '09.09.16',
                '16:10'
            ]
        ];

        return [$key0Leg0, $key0Leg3, $key1Leg2];
    }

    /**
     * @dataProvider dataProvider
     * @param int $tripKey
     * @param int $numLegs
     * @param array $leg
     */
    public function test_response($tripKey, $numLegs, $leg)
    {
        list($legId, $legName, $legType, $origin, $dest, $notes, $journey, $depDate, $depTime, $arrDate, $arrTime) = $leg;
        list($originName, $originType, $originRouteIdx, $originTime, $originDate, $originTrack, $originRtTrack) = $origin;
        list($destName, $destType, $destRouteIdx, $destTime, $destDate, $destTrack, $destRtTrack) = $dest;

        $data = $this->response[$tripKey];
        $this->assertCount($numLegs, $data->getLegs());
        $leg = $data->getLegs()[$legId];
        $this->assertEquals($legName, $leg->getName());
        $this->assertEquals($legType, $leg->getType());

        $depDate = date_create_from_format('d.m.y H:i', sprintf('%s %s', $depDate, $depTime));
        $this->assertEquals($depDate->format('Y-m-d H:i'), $data->getDepartureDate()->format('Y-m-d H:i'));
        $arrDate = date_create_from_format('d.m.y H:i', sprintf('%s %s', $arrDate, $arrTime));
        $this->assertEquals($arrDate->format('Y-m-d H:i'), $data->getArrivalDate()->format('Y-m-d H:i'));

        // Origin
        $this->assertEquals($originName, $leg->getOrigin()->getName());
        $this->assertEquals($originType, $leg->getOrigin()->getType());
        $this->assertEquals($originRouteIdx, $leg->getOrigin()->getRouteIdx());
        $date = date_create_from_format('d.m.y H:i', sprintf('%s %s', $originDate, $originTime));
        $this->assertEquals($date->format('Y-m-d H:i'), $leg->getOrigin()->getDate()->format('Y-m-d H:i'));
        $this->assertEquals($originTrack, $leg->getOrigin()->getTrack());
        $this->assertEquals($originRtTrack, $leg->getOrigin()->getRtTrack());

        // Dest
        $this->assertEquals($destName, $leg->getDestination()->getName());
        $this->assertEquals($destType, $leg->getDestination()->getType());
        $this->assertEquals($destRouteIdx, $leg->getDestination()->getRouteIdx());
        $date = date_create_from_format('d.m.y H:i', sprintf('%s %s', $destDate, $destTime));
        $this->assertEquals($date->format('Y-m-d H:i'), $leg->getDestination()->getDate()->format('Y-m-d H:i'));
        $this->assertEquals($destTrack, $leg->getDestination()->getTrack());
        $this->assertEquals($destRtTrack, $leg->getDestination()->getRtTrack());

        $this->assertEquals($journey, $leg->getJournyDetails());
        $this->assertCount(count($notes), $leg->getNotes());
        foreach ($notes as $i => $note) {
            $this->assertEquals($note, $leg->getNotes()[$i]);
        }

    }

}

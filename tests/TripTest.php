<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest;

use DateTime;
use Lsv\Rejseplan\Response\CoordinateResponse;
use Lsv\Rejseplan\Response\Location\Stop;
use Lsv\Rejseplan\Trip;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class TripTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_multiple_legs(): void
    {
        $client = new MockHttpClient(
            [
                new MockResponse(
                    file_get_contents(
                        __DIR__
                        .'/stubs/trip.json'
                    )
                ),
            ]
        );

        $trip = new Trip($client);
        $response = $trip->request('008600696', '008600669');
        $this->assertCount(4, $response->trips);
        $trips = $response->trips[0];
        $this->assertCount(5, $trips->legs);
        $leg = $trips->legs[1];

        $this->assertSame('Bus 55E', $leg->getName());
        $this->assertSame('BUS', $leg->getType());
        $this->assertSame('55E', $leg->getLine());

        $this->assertSame('Farum Bytorv (Frederiksborgvej)', $leg->getOrigin()->getName());
        $this->assertSame('ST', $leg->getOrigin()->getType());
        $this->assertSame('2019-10-03 10:53', $leg->getOrigin()->getScheduledDate()->format('Y-m-d H:i'));

        $this->assertSame('Allerød St.', $leg->getDestination()->getName());
        $this->assertSame('ST', $leg->getDestination()->getType());
        $this->assertSame('2019-10-03 11:09', $leg->getDestination()->getScheduledDate()->format('Y-m-d H:i'));
        $this->assertSame('2019-10-03 11:07', $leg->getDestination()->getRealtimeDate()->format('Y-m-d H:i'));

        $this->assertCount(1, $leg->getNotes());
        $this->assertSame('Retning: Allerød St.;', $leg->notes[0]->getText());
        $this->assertSame(
            'http://xmlopen.rejseplanen.dk/bin/rest.exe/journeyDetail?ref=637818%2F237287%2F127552%2F148832%2F86%3Fdate%3D03.10.19%26station_evaId%3D9352%26format%3Djson',
            $leg->getJourney()
        );

        $this->assertCount(3, $response->trips[3]->legs[0]->getMessages());

        $this->assertSame('30', $response->getTrips()[1]->getLegs()[3]->getOrigin()->getRouteIdx());
        $this->assertSame('1', $response->getTrips()[1]->getLegs()[3]->getOrigin()->getScheduledTrack());
        $this->assertSame('1', $response->getTrips()[1]->getLegs()[3]->getOrigin()->getRealtimeTrack());
    }

    /**
     * @test
     */
    public function can_get_with_single_trip(): void
    {
        $client = new MockHttpClient(
            [
                new MockResponse(
                    file_get_contents(
                        __DIR__
                        .'/stubs/trip_single.json'
                    )
                ),
            ]
        );

        $trip = new Trip($client);
        $response = $trip->request('000010008', '008600650');
        $this->assertCount(1, $response->getTrips()[0]->legs);
    }

    /**
     * @test
     */
    public function can_get_with_single_leg(): void
    {
        $client = new MockHttpClient(
            [
                new MockResponse(
                    file_get_contents(
                        __DIR__
                        .'/stubs/trip_single_leg.json'
                    )
                ),
            ]
        );

        $trip = new Trip($client);
        $response = $trip->request('000010008', '008600650');
        $this->assertCount(1, $response->trips[0]->getLegs());
    }

    /**
     * @test
     */
    public function can_set_url_parameters(): void
    {
        $client = new MockHttpClient(
            [
                new MockResponse(
                    file_get_contents(
                        __DIR__
                        .'/stubs/trip.json'
                    )
                ),
            ]
        );

        $trip = new Trip($client);
        $trip
            ->setVia('00108000')
            ->setWalkingDistance(500, 1000)
            ->setBicycleDistance(500, 1000)
            ->setDate(new DateTime('2019-05-23 14:22'))
            ->setDontUseBus()
            ->setDontUseMetro()
            ->setDontUseTrain();

        $trip->request(123, 123);
        $query = $trip->getQuery();

        $this->assertSame('00108000', $query['viaId']);

        $this->assertSame(500, $query['maxWalkingDistanceDep']);
        $this->assertSame(1000, $query['maxWalkingDistanceDest']);

        $this->assertTrue($query['useBicycle']);
        $this->assertSame(500, $query['maxCyclingDistanceDep']);
        $this->assertSame(1000, $query['maxCyclingDistanceDest']);

        $this->assertSame('23.05.19', $query['date']);
        $this->assertSame('14:22', $query['time']);

        $this->assertFalse($query['useTog']);
        $this->assertFalse($query['useBus']);
        $this->assertFalse($query['useMetro']);
    }

    /**
     * @test
     */
    public function can_get_trip_via_coordinates(): void
    {
        $client = new MockHttpClient(
            [
                new MockResponse(
                    file_get_contents(
                        __DIR__
                        .'/stubs/trip.json'
                    )
                ),
            ]
        );

        $trip = new Trip($client);

        $coord = new CoordinateResponse();
        $coord->latitude = 55.665476;
        $coord->longitude = 12.566461;

        $trip->request($coord, 'Vejnavn 1, 2670 Greve');
        $query = $trip->getQuery();

        $this->assertSame('12566461', $query['originCoordX']);
        $this->assertSame('55665476', $query['originCoordY']);
        $this->assertSame('Vejnavn 1, 2670 Greve', $query['destCoordName']);
    }

    /**
     * @test
     */
    public function can_get_trip_via_stop_location(): void
    {
        $client = new MockHttpClient(
            [
                new MockResponse(
                    file_get_contents(
                        __DIR__
                        .'/stubs/trip.json'
                    )
                ),
            ]
        );

        $trip = new Trip($client);

        $coord = new Stop();
        $coord->id = '001023034';

        $trip->request($coord, 'Vejnavn 1, 2670 Greve');
        $query = $trip->getQuery();

        $this->assertSame('001023034', $query['originId']);
        $this->assertSame('Vejnavn 1, 2670 Greve', $query['destCoordName']);
    }
}

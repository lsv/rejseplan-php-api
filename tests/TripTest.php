<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest;

use DateTime;
use Lsv\Rejseplan\Response\CoordinateResponse;
use Lsv\Rejseplan\Response\Location\Stop;
use Lsv\Rejseplan\Trip;

class TripTest extends AbstractTest
{
    /**
     * @test
     */
    public function canGetMultipleLegs(): void
    {
        $this->setClient(__DIR__.'/stubs/trip.json');

        $trip = new Trip('008600696', '008600669');
        $response = $trip->request();
        $this->assertCount(4, $response->trips);
        $trips = $response->trips[0];
        $this->assertCount(5, $trips->legs);
        $leg = $trips->legs[1];

        $this->assertSame('Bus 55E', $leg->name);
        $this->assertSame('BUS', $leg->type);
        $this->assertSame('55E', $leg->line);

        $this->assertSame('Farum Bytorv (Frederiksborgvej)', $leg->origin->name);
        $this->assertSame('ST', $leg->origin->type);
        $this->assertSame('2019-10-03 10:53', $leg->origin->scheduledDate->format('Y-m-d H:i'));

        $this->assertSame('Allerød St.', $leg->destination->name);
        $this->assertSame('ST', $leg->destination->type);
        $this->assertSame('2019-10-03 11:09', $leg->destination->scheduledDate->format('Y-m-d H:i'));
        $this->assertSame('2019-10-03 11:07', $leg->destination->realtimeDate->format('Y-m-d H:i'));

        $this->assertCount(1, $leg->notes);
        $this->assertSame('Retning: Allerød St.;', $leg->notes[0]->text);
        $this->assertSame(
            'https://xmlopen.rejseplanen.dk/bin/rest.exe/journeyDetail?ref=637818%2F237287%2F127552%2F148832%2F86%3Fdate%3D03.10.19%26station_evaId%3D9352%26format%3Djson',
            $leg->journeyDetails
        );

        $this->assertCount(3, $response->trips[3]->legs[0]->messages);

        $origin = $response->trips[1]->legs[3]->origin;
        $this->assertSame('30', $origin->routeIdx);
        $this->assertSame('1', $origin->scheduledTrack);
        $this->assertSame('1', $origin->realtimeTrack);
    }

    /**
     * @test
     */
    public function canGetWithSingleTrip(): void
    {
        $this->setClient(__DIR__.'/stubs/trip_single.json');

        $trip = new Trip('000010008', '008600650');
        $response = $trip->request();
        $this->assertCount(1, $response->trips[0]->legs);
    }

    /**
     * @test
     */
    public function canGetWithSingleLeg(): void
    {
        $this->setClient(__DIR__.'/stubs/trip_single_leg.json');

        $trip = new Trip('000010008', '008600650');
        $response = $trip->request();
        $this->assertCount(1, $response->trips[0]->legs);
    }

    /**
     * @test
     */
    public function canSetUrlParameters(): void
    {
        $this->setClient(__DIR__.'/stubs/trip.json');

        $trip = new Trip(123, 123);
        $trip
            ->setVia('00108000')
            ->setWalkingDistance(500, 1000)
            ->setBicycleDistance(500, 1000)
            ->setDate(new DateTime('2019-05-23 14:22'))
            ->setDontUseBus()
            ->setDontUseMetro()
            ->setDontUseTrain();

        $trip->request();
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
    public function canGetTripViaCoordinates(): void
    {
        $this->setClient(__DIR__.'/stubs/trip.json');

        $coord = new CoordinateResponse();
        $coord->latitude = 55.665476;
        $coord->longitude = 12.566461;
        $trip = new Trip($coord, 'Vejnavn 1, 2670 Greve');

        $trip->request();
        $query = $trip->getQuery();

        $this->assertSame('12566461', $query['originCoordX']);
        $this->assertSame('55665476', $query['originCoordY']);
        $this->assertSame('Vejnavn 1, 2670 Greve', $query['destCoordName']);
    }

    /**
     * @test
     */
    public function canGetTripViaStopLocation(): void
    {
        $this->setClient(__DIR__.'/stubs/trip.json');

        $coord = new Stop();
        $coord->id = '001023034';
        $trip = new Trip($coord, 'Vejnavn 1, 2670 Greve');

        $trip->request();
        $query = $trip->getQuery();

        $this->assertSame('001023034', $query['originId']);
        $this->assertSame('Vejnavn 1, 2670 Greve', $query['destCoordName']);
    }
}

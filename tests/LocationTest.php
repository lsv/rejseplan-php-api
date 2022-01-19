<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest;

use Lsv\Rejseplan\Location;

class LocationTest extends AbstractTest
{
    /**
     * @test
     */
    public function canGetMultipleLocations(): void
    {
        $this->setClient(__DIR__.'/stubs/location.json');
        $location = new Location('123');
        $response = $location->request();

        $this->assertCount(5, $response->stops);
        $stop = $response->stops[1];
        $this->assertSame('Hovedbanegården, Tivoli (Bernstorffsgade)',
            $stop->name);
        $this->assertSame('000010845', $stop->id);
        $this->assertSame('000010845', $stop->id);
        $this->assertSame(55.672578, $stop->coordinate->latitude);
        $this->assertSame(12.566488, $stop->coordinate->longitude);

        $this->assertCount(6, $response->pois);
        $poi = $response->pois[0];
        $this->assertSame('Tivoli, Forlystelsespark, København', $poi->name);
        $this->assertSame(55.673171, $poi->coordinate->latitude);
        $this->assertSame(12.566084, $poi->coordinate->longitude);

        $this->assertCount(3, $response->addresses);
        $addr = $response->addresses[1];
        $this->assertSame('Tivekrogen 5863 Ferritslev Fyn, Faaborg-Midtfyn Ko',
            $addr->name);
        $this->assertSame(55.300676, $addr->coordinate->latitude);
        $this->assertSame(10.605473, $addr->coordinate->longitude);
    }

    /**
     * @test
     */
    public function canGetSingleStopLocation(): void
    {
        $this->setClient(__DIR__.'/stubs/location_single_stop.json');

        $location = new Location('123');
        $response = $location->request();
        $this->assertCount(1, $response->stops);
        $this->assertCount(0, $response->pois);
        $this->assertCount(0, $response->addresses);
    }

    /**
     * @test
     */
    public function canGetSingleAddressLocation(): void
    {
        $this->setClient(__DIR__.'/stubs/location_single_address.json');
        $location = new Location('123');
        $response = $location->request();
        $this->assertCount(0, $response->stops);
        $this->assertCount(0, $response->pois);
        $this->assertCount(1, $response->addresses);
    }

    /**
     * @test
     */
    public function canGetSinglePoisLocation(): void
    {
        $this->setClient(__DIR__.'/stubs/location_single_poi.json');

        $location = new Location('123');
        $response = $location->request();
        $this->assertCount(0, $response->stops);
        $this->assertCount(1, $response->pois);
        $this->assertCount(0, $response->addresses);
    }

    /**
     * @test
     */
    public function canSetUrlParameters(): void
    {
        $this->setClient(__DIR__.'/stubs/location_single_poi.json');
        $location = new Location('123');
        $location
            ->doNotIncludeAddresses()
            ->doNotIncludePOI()
            ->doNotIncludeStops();

        $location->request();
        $query = $location->getQuery();

        $this->assertFalse($query['include_stops']);
        $this->assertFalse($query['include_addresses']);
        $this->assertFalse($query['include_pois']);
    }
}

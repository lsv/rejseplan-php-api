<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest;

use Lsv\Rejseplan\Location;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class LocationTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_multiple_locations(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/location.json')),
        ]);

        $location = new Location($client);
        $response = $location->request('123');

        $this->assertCount(5, $response->getStops());
        $stop = $response->stops[1];
        $this->assertSame('Hovedbanegården, Tivoli (Bernstorffsgade)',
            $stop->name);
        $this->assertSame('000010845', $stop->id);
        $this->assertSame('000010845', $stop->getId());
        $this->assertSame(55.672578, $stop->coordinate->latitude);
        $this->assertSame(12.566488, $stop->coordinate->longitude);

        $this->assertCount(6, $response->getPois());
        $poi = $response->pois[0];
        $this->assertSame('Tivoli, Forlystelsespark, København', $poi->getName());
        $this->assertSame(55.673171, $poi->getCoordinate()->latitude);
        $this->assertSame(12.566084, $poi->getCoordinate()->longitude);

        $this->assertCount(3, $response->getAddresses());
        $addr = $response->addresses[1];
        $this->assertSame('Tivekrogen 5863 Ferritslev Fyn, Faaborg-Midtfyn Ko',
            $addr->name);
        $this->assertSame(55.300676, $addr->coordinate->latitude);
        $this->assertSame(10.605473, $addr->coordinate->longitude);
    }

    /**
     * @test
     */
    public function can_get_single_stop_location(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/location_single_stop.json')),
        ]);

        $location = new Location($client);
        $response = $location->request('123');
        $this->assertCount(1, $response->stops);
        $this->assertCount(0, $response->pois);
        $this->assertCount(0, $response->addresses);
    }

    /**
     * @test
     */
    public function can_get_single_address_location(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/location_single_address.json')),
        ]);

        $location = new Location($client);
        $response = $location->request('123');
        $this->assertCount(0, $response->stops);
        $this->assertCount(0, $response->pois);
        $this->assertCount(1, $response->addresses);
    }

    /**
     * @test
     */
    public function can_get_single_pois_location(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/location_single_poi.json')),
        ]);

        $location = new Location($client);
        $response = $location->request('123');
        $this->assertCount(0, $response->stops);
        $this->assertCount(1, $response->pois);
        $this->assertCount(0, $response->addresses);
    }

    /**
     * @test
     */
    public function can_set_url_parameters(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/location_single_poi.json')),
        ]);

        $location = new Location($client);
        $location
            ->doNotIncludeAddresses()
            ->doNotIncludePOI()
            ->doNotIncludeStops();

        $location->request('123');
        $query = $location->getQuery();

        $this->assertFalse($query['include_stops']);
        $this->assertFalse($query['include_addresses']);
        $this->assertFalse($query['include_pois']);
    }
}

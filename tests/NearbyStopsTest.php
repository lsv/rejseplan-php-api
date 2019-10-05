<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest;

use Lsv\Rejseplan\NearbyStops;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class NearbyStopsTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_multiple_stops(): void
    {
        $client = new MockHttpClient(
            [
                new MockResponse(
                    file_get_contents(
                        __DIR__
                        .'/stubs/nearbystops.json'
                    )
                ),
            ]
        );

        $board = new NearbyStops($client);
        $response = $board->request(53, 11);

        $this->assertCount(27, $response->stops);

        $stop = $response->getStops()[5];

        $this->assertSame('Hovedbanegården (Vesterbrogade)', $stop->name);
        $this->assertSame('1152', $stop->id);
        $this->assertSame('188', $stop->getDistance());
        $this->assertSame(55.674232, $stop->coordinate->latitude);
        $this->assertSame(12.563630, $stop->coordinate->longitude);
    }

    /**
     * @test
     */
    public function can_get_single_stop(): void
    {
        $client = new MockHttpClient(
            [
                new MockResponse(
                    file_get_contents(
                        __DIR__
                        .'/stubs/nearbystops_single.json'
                    )
                ),
            ]
        );

        $obj = new NearbyStops($client);
        $response = $obj->request(53, 11);

        $this->assertCount(1, $response->stops);
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
                        .'/stubs/nearbystops_single.json'
                    )
                ),
            ]
        );

        $obj = new NearbyStops($client);
        $obj
            ->setMaxResults(5)
            ->setRadius(100);

        $obj->request(53, 11);
        $query = $obj->getQuery();

        $this->assertSame(100, $query['maxRadius']);
        $this->assertSame(5, $query['maxNumber']);
    }
}

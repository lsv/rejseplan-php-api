<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest;

use Lsv\Rejseplan\NearbyStops;

class NearbyStopsTest extends AbstractTest
{
    /**
     * @test
     */
    public function canGetMultipleStops(): void
    {
        $this->setClient(__DIR__.'/stubs/nearbystops.json');

        $board = new NearbyStops(53, 11);
        $response = $board->request();

        $this->assertCount(27, $response->stops);

        $stop = $response->stops[5];

        $this->assertSame('Hovedbanegården (Vesterbrogade)', $stop->name);
        $this->assertSame('1152', $stop->id);
        $this->assertSame('188', $stop->distance);
        $this->assertSame(55.674232, $stop->coordinate->latitude);
        $this->assertSame(12.563630, $stop->coordinate->longitude);
    }

    /**
     * @test
     */
    public function canGetSingleStop(): void
    {
        $this->setClient(__DIR__.'/stubs/nearbystops_single.json');
        $obj = new NearbyStops(53, 11);
        $response = $obj->request();

        $this->assertCount(1, $response->stops);
    }

    /**
     * @test
     */
    public function canSetUrlParameters(): void
    {
        $this->setClient(__DIR__.'/stubs/nearbystops_single.json');
        $obj = new NearbyStops(53, 11);
        $obj
            ->setMaxResults(5)
            ->setRadius(100);

        $obj->request();
        $query = $obj->getQuery();

        $this->assertSame(100, $query['maxRadius']);
        $this->assertSame(5, $query['maxNumber']);
    }
}

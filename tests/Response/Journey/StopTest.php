<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest\Response\Journey;

use Lsv\Rejseplan\Response\CoordinateResponse;
use Lsv\Rejseplan\Response\Journey\Stop;
use PHPUnit\Framework\TestCase;

class StopTest extends TestCase
{
    /**
     * @var Stop
     */
    private $stop;

    /**
     * @test
     */
    public function index(): void
    {
        $this->stop->setRouteIdx(1);
        $this->assertSame(1, $this->stop->getIndex());
    }

    /**
     * @test
     */
    public function scheduledArrivalDate(): void
    {
        $this->stop->setArrDate('23.11.16');
        $this->stop->setArrTime('11:45');
        $this->assertSame('2016-11-23 11:45', $this->stop->getScheduledArrival()->format('Y-m-d H:i'));
    }

    /**
     * @test
     */
    public function realtimeArrivalDate(): void
    {
        $this->stop->setRtArrDate('23.11.16');
        $this->stop->setRtArrTime('11:45');
        $this->assertSame('2016-11-23 11:45', $this->stop->getRealtimeArrival()->format('Y-m-d H:i'));
    }

    /**
     * @test
     */
    public function scheduledDepartureDate(): void
    {
        $this->stop->setDepDate('23.11.16');
        $this->stop->setDepTime('11:45');
        $this->assertSame('2016-11-23 11:45', $this->stop->getScheduledDeparture()->format('Y-m-d H:i'));
    }

    /**
     * @test
     */
    public function realtimeDepartureDate(): void
    {
        $this->stop->setRtDepDate('23.11.2016');
        $this->stop->setRtDepTime('11:45');
        $this->assertSame('2016-11-23 11:45', $this->stop->getRealtimeDeparture()->format('Y-m-d H:i'));
    }

    /**
     * @test
     */
    public function track(): void
    {
        $this->stop->setTrack(1);
        $this->assertSame(1, $this->stop->getScheduledTrack());
        $this->stop->setRtTrack(2);
        $this->assertSame(2, $this->stop->getRealtimeTrack());
        $this->assertTrue($this->stop->isTrackChanged());
    }

    /**
     * @test
     */
    public function arrivalDelayed(): void
    {
        $this->assertFalse($this->stop->isArrivalDelayed());

        $this->stop->setArrDate('23.11.16');
        $this->stop->setArrTime('11:45');

        $this->stop->setRtArrDate('23.11.16');
        $this->stop->setRtArrTime('11:45');

        $this->assertFalse($this->stop->isArrivalDelayed());
    }

    /**
     * @test
     */
    public function departureDelayed(): void
    {
        $this->assertFalse($this->stop->isDepartureDelayed());

        $this->stop->setDepDate('23.11.16');
        $this->stop->setDepTime('11:45');

        $this->stop->setRtDepDate('23.11.2016');
        $this->stop->setRtDepTime('11:45');

        $this->assertFalse($this->stop->isDepartureDelayed());
    }

    /**
     * @test
     */
    public function coordinate(): void
    {
        $coordinate = new CoordinateResponse();
        $coordinate->longitude = 11;
        $coordinate->latitude = 53;
        $this->stop->coordinate = $coordinate;
        $this->assertSame(11, $this->stop->getCoordinate()->longitude);
        $this->assertSame(53, $this->stop->getCoordinate()->latitude);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->stop = new Stop();
    }
}

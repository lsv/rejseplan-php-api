<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest\Response\Trip;

use Lsv\Rejseplan\Response\Trip\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    /**
     * @var Address
     */
    private $address;

    /**
     * @test
     */
    public function scheduleddate(): void
    {
        $this->address->setDate('23.11.19');
        $this->assertSame('2019-11-23', $this->address->scheduledDate->format('Y-m-d'));
        $this->address->setTime('11:45');
        $this->assertSame('11:45', $this->address->scheduledDate->format('H:i'));
    }

    /**
     * @test
     */
    public function realtimedate(): void
    {
        $this->address->setRtDate('5.6.20');
        $this->assertSame('2020-06-05', $this->address->realtimeDate->format('Y-m-d'));
        $this->address->setRtTime('9:05');
        $this->assertSame('09:05', $this->address->realtimeDate->format('H:i'));
    }

    /**
     * @test
     */
    public function track(): void
    {
        $this->address->setTrack(1);
        $this->assertSame('1', $this->address->scheduledTrack);
        $this->address->setRtTrack(2);
        $this->assertSame('2', $this->address->realtimeTrack);
    }

    /**
     * @test
     */
    public function delayed(): void
    {
        $this->address->setDate('23.11.19');
        $this->address->setTime('11:45');

        $this->address->setRtDate('23.11.19');
        $this->address->setRtTime('11:49');

        $this->assertTrue($this->address->isDelayed());
    }

    /**
     * @test
     */
    public function notDelayed(): void
    {
        $this->address->setDate('23.11.19');
        $this->address->setTime('11:45');

        $this->address->setRtDate('23.11.19');
        $this->address->setRtTime('11:48');

        $this->assertFalse($this->address->isDelayed());
    }

    /**
     * @test
     */
    public function trackChanged(): void
    {
        $this->address->setTrack(1);
        $this->address->setRtTrack(2);
        $this->assertTrue($this->address->isTrackChanged());
    }

    /**
     * @test
     */
    public function trackNotChanged(): void
    {
        $this->address->setTrack(1);
        $this->address->setRtTrack(1);
        $this->assertFalse($this->address->isTrackChanged());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->address = new Address();
    }
}

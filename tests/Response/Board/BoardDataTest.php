<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest\Response\Board;

use Lsv\Rejseplan\Response\Board\BoardData;
use PHPUnit\Framework\TestCase;

class BoardDataTest extends TestCase
{
    /**
     * @var BoardData
     */
    private $board;

    /**
     * @test
     */
    public function scheduleddate(): void
    {
        $this->board->setDate('23.11.19');
        $this->assertSame('2019-11-23', $this->board->scheduledDate->format('Y-m-d'));
        $this->board->setTime('11:45');
        $this->assertSame('11:45', $this->board->scheduledDate->format('H:i'));
    }

    /**
     * @test
     */
    public function realtimedate(): void
    {
        $this->board->setRtDate('5.6.20');
        $this->assertSame('2020-06-05', $this->board->realtimeDate->format('Y-m-d'));
        $this->board->setRtTime('9:05');
        $this->assertSame('09:05', $this->board->realtimeDate->format('H:i'));
    }

    /**
     * @test
     */
    public function track(): void
    {
        $this->board->setTrack('1');
        $this->assertSame('1', $this->board->scheduledTrack);
        $this->board->setRtTrack(2);
        $this->assertSame('2', $this->board->realtimeTrack);
    }

    public function testNullTrack(): void
    {
        $this->board->setTrack(null);
        $this->assertNull($this->board->scheduledTrack);
        $this->board->setRtTrack(null);
        $this->assertNull($this->board->realtimeTrack);
    }

    /**
     * @test
     */
    public function messages(): void
    {
        $this->assertFalse($this->board->hasMessages);
        $this->board->setMessages('1');
        $this->assertTrue($this->board->hasMessages);
    }

    /**
     * @test
     */
    public function journeyref(): void
    {
        $this->board->setJourneyDetailRef([]);
        $this->assertNull($this->board->journeyDetails);

        $this->board->setJourneyDetailRef(['ref' => 'journey']);
        $this->assertSame('journey', $this->board->journeyDetails);
    }

    /**
     * @test
     */
    public function delayed(): void
    {
        $this->board->setDate('23.11.19');
        $this->board->setTime('11:45');

        $this->board->setRtDate('23.11.19');
        $this->board->setRtTime('11:49');

        $this->assertTrue($this->board->isDelayed());
    }

    /**
     * @test
     */
    public function notDelayed(): void
    {
        $this->board->setDate('23.11.19');
        $this->board->setTime('11:45');

        $this->board->setRtDate('23.11.19');
        $this->board->setRtTime('11:48');

        $this->assertFalse($this->board->isDelayed());
    }

    public function testNotDelayedNoRtTime(): void
    {
        $this->board->setDate('23.11.19');
        $this->board->setTime('11:45');
        $this->assertFalse($this->board->isDelayed());
    }

    /**
     * @test
     */
    public function trackChanged(): void
    {
        $this->board->setTrack(1);
        $this->board->setRtTrack(2);
        $this->assertTrue($this->board->isTrackChanged());
    }

    /**
     * @test
     */
    public function trackNotChanged(): void
    {
        $this->board->setTrack(1);
        $this->board->setRtTrack(1);
        $this->assertFalse($this->board->isTrackChanged());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $class = new class() extends BoardData {
        };

        $this->board = $class;
    }
}

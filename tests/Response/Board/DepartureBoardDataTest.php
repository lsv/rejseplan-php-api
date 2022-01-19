<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest\Response\Board;

use Lsv\Rejseplan\Response\Board\DepartureBoardData;
use PHPUnit\Framework\TestCase;

class DepartureBoardDataTest extends TestCase
{
    /**
     * @var DepartureBoardData
     */
    private $board;

    /**
     * @test
     */
    public function finalStop(): void
    {
        $this->board->finalStop = 'Hello World';
        $this->assertSame('Hello World', $this->board->finalStop);
    }

    /**
     * @test
     */
    public function direction(): void
    {
        $this->board->direction = 'Hello World';
        $this->assertSame('Hello World', $this->board->direction);
    }

    protected function setUp(): void
    {
        $this->board = new DepartureBoardData();
    }
}

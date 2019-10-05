<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest\Response\Board;

use Lsv\Rejseplan\Response\Board\ArrivalBoardData;
use PHPUnit\Framework\TestCase;

class ArrivalBoardDataTest extends TestCase
{
    /**
     * @var ArrivalBoardData
     */
    private $board;

    /**
     * @test
     */
    public function origin(): void
    {
        $this->board->origin = 'Hello World';
        $this->assertSame('Hello World', $this->board->origin);
    }

    protected function setUp()
    {
        $this->board = new ArrivalBoardData();
    }
}

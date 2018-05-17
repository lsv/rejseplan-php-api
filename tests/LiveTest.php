<?php

namespace RejseplanApiTest\Services;

use PHPUnit\Framework\TestCase;
use RejseplanApi\Services\DepartureBoard;

class LiveTest extends TestCase
{
    public function setUp()
    {
        if (!isset($_ENV['REJSEPLAN_BASE_URL'])) {
            $this->markTestSkipped('You will need to set the "REJSEPLAN_BASE_URL" environment variable to run this test');
        }
    }

    public function test_response(): void
    {
        $board = new DepartureBoard($_ENV['REJSEPLAN_BASE_URL']);
        $board->setLocation('000010845');
        $board->call();
    }
}

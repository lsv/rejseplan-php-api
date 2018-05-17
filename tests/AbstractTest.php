<?php

namespace RejseplanApiTest;

use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{
    /**
     * @param string $exception
     * @param string $message
     * @param int $code
     */
    public function setExpectedException($exception, $message = null, $code = null): void
    {
        $this->expectException($exception);
        if ($message) {
            $this->expectExceptionMessage($message);
        }
        if ($code) {
            $this->expectExceptionCode($code);
        }
    }
}

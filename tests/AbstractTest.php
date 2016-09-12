<?php
namespace RejseplanApiTest;

abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $exception
     * @param string $message
     * @param int $code
     */
    public function setExpectedException($exception, $message = null, $code = null)
    {
        if (method_exists($this, 'expectException')) {
            $this->expectException($exception);
            if ($message) {
                $this->expectExceptionMessage($message);
            }

            if ($code) {
                $this->expectExceptionCode($code);
            }

        } else {
            parent::setExpectedException($exception, $message, $code);
        }
    }

}

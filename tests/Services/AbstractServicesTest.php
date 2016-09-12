<?php
namespace RejseplanApiTest\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use RejseplanApi\Coordinate;
use RejseplanApi\Services\Response\LocationResponse;
use RejseplanApi\Services\Response\StopLocationResponse;
use RejseplanApiTest\AbstractTest;

abstract class AbstractServicesTest extends AbstractTest
{

    protected function getCoordinate()
    {
        return new Coordinate(12.566488, 55.672578);
    }

    /**
     * @param string $type
     * @return LocationResponse
     */
    protected function getLocationResponse($type = LocationResponse::LOCATIONTYPE_STOP)
    {
        $loc = $this->createMock(LocationResponse::class);
        $loc->method('getName')
            ->willReturn('Hovedbanegården, Tivoli (Bernstorffsgade)')
        ;
        $loc->method('getCoordinate')
            ->willReturn($this->getCoordinate())
        ;
        $loc->method('getId')
            ->willReturn($type === LocationResponse::LOCATIONTYPE_STOP ? '000010845' : null)
        ;
        $loc->method('isStop')
            ->willReturn($type === LocationResponse::LOCATIONTYPE_STOP)
        ;
        $loc->method('isAddress')
            ->willReturn($type === LocationResponse::LOCATIONTYPE_ADDRESS)
        ;
        $loc->method('isPOI')
            ->willReturn($type === LocationResponse::LOCATIONTYPE_POI)
        ;
        return $loc;
    }

    /**
     * @return StopLocationResponse
     */
    protected function getStopLocationResponse()
    {
        $loc = $this->createMock(StopLocationResponse::class);
        $loc->method('getId')
            ->willReturn('987654321')
        ;
        $loc->method('getName')
            ->willReturn('Somewhere in Denmark')
        ;
        $loc->method('getCoordinate')
            ->willReturn(new Coordinate(55.123456, 12.654321))
        ;
        $loc->method('getDistance')
            ->willReturn(10)
        ;
        return $loc;
    }

    protected function getBaseUrl()
    {
        return '';
    }

    /**
     * @param MockHandler $mock
     * @return Client
     */
    protected function getClient(MockHandler $mock)
    {
        $handler = HandlerStack::create($mock);
        return new Client(['handler' => $handler]);
    }

    /**
     * @param string $body
     * @return MockHandler
     */
    protected function getClientMockHandler($body)
    {
        return new MockHandler([
            new Response(200, [], $body)
        ]);
    }

    /**
     * @param string $body
     * @return Client
     */
    protected function getClientWithMock($body)
    {
        return $this->getClient($this->getClientMockHandler($body));
    }

}

<?php

namespace RejseplanApiTest\Services;

use RejseplanApi\Services\NearbyStops;
use RejseplanApi\Utils\Coordinate;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class NearbyStopsTest extends AbstractServicesTest
{
    public function test_url_setCoordinate(): void
    {
        $coordinate = $this->getCoordinate();

        $location = new NearbyStops($this->getBaseUrl());
        $location->setCoordinate($coordinate);

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/stopsNearby', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals($coordinate->getLatitudeAsInt(), $query['coordX']);
        $this->assertEquals($coordinate->getLongitudeAsInt(), $query['coordY']);
    }

    public function test_url_setCoordinate_astext(): void
    {
        $coordinate = new Coordinate('11.566488', '56.672578');

        $location = new NearbyStops($this->getBaseUrl());
        $location->setCoordinate($coordinate);

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/stopsNearby', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals(11566488, $query['coordX']);
        $this->assertEquals(56672578, $query['coordY']);
    }

    public function test_url_setMaxResults(): void
    {
        $coordinate = $this->getCoordinate();

        $location = new NearbyStops($this->getBaseUrl());
        $location->setCoordinate($coordinate);
        $location->setMaxResults(10);

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/stopsNearby', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals($coordinate->getLatitudeAsInt(), $query['coordX']);
        $this->assertEquals($coordinate->getLongitudeAsInt(), $query['coordY']);
        $this->assertEquals('10', $query['maxNumber']);
    }

    public function test_url_setRadius(): void
    {
        $coordinate = $this->getCoordinate();

        $location = new NearbyStops($this->getBaseUrl());
        $location->setCoordinate($coordinate);
        $location->setRadius(1500);

        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/stopsNearby', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals($coordinate->getLatitudeAsInt(), $query['coordX']);
        $this->assertEquals($coordinate->getLongitudeAsInt(), $query['coordY']);
        $this->assertEquals('1500', $query['maxRadius']);
    }

    public function test_not_configured_correct(): void
    {
        $this->setExpectedException(MissingOptionsException::class, 'The required options "coordX", "coordY" are missing.');
        $location = new NearbyStops($this->getBaseUrl());
        $location->call();
    }

    public function test_single(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/nearbystops_single.json'));
        $location = new NearbyStops($this->getBaseUrl(), $client);
        $location->setCoordinate($this->getCoordinate());
        $location->setMaxResults(10);
        $location->setRadius(1500);
        $response = $location->call();

        $this->assertCount(1, $response);

        $loc1 = $response[0];

        $this->assertEquals('8600626', $loc1->getId());
        $this->assertEquals('København H', $loc1->getName());
        $this->assertEquals('12.565562', $loc1->getCoordinate()->getLatitude());
        $this->assertEquals('55.673063', $loc1->getCoordinate()->getLongitude());
        $this->assertEquals('14', $loc1->getDistance());
    }

    public function test_response(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/nearbystops.json'));
        $location = new NearbyStops($this->getBaseUrl(), $client);
        $location->setCoordinate($this->getCoordinate());
        $location->setMaxResults(10);
        $location->setRadius(1500);
        $response = $location->call();

        $this->assertCount(27, $response);

        $loc1 = $response[0];

        $this->assertEquals('8600626', $loc1->getId());
        $this->assertEquals('København H', $loc1->getName());
        $this->assertEquals('12.565562', $loc1->getCoordinate()->getLatitude());
        $this->assertEquals('55.673063', $loc1->getCoordinate()->getLongitude());
        $this->assertEquals('14', $loc1->getDistance());

        $loc2 = $response[5];

        $this->assertEquals('1152', $loc2->getId());
        $this->assertEquals('Hovedbanegården (Vesterbrogade)', $loc2->getName());
        $this->assertEquals('12.563630', $loc2->getCoordinate()->getLatitude());
        $this->assertEquals('55.674232', $loc2->getCoordinate()->getLongitude());
        $this->assertEquals('188', $loc2->getDistance());

        $loc3 = $response[12];

        $this->assertEquals('27536', $loc3->getId());
        $this->assertEquals('Vesterport St. (Ved Vesterport)', $loc3->getName());
        $this->assertEquals('12.563998', $loc3->getCoordinate()->getLatitude());
        $this->assertEquals('55.675850', $loc3->getCoordinate()->getLongitude());
        $this->assertEquals('329', $loc3->getDistance());
    }

    public function test_errored(): void
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/error.txt'));
        $location = new NearbyStops($this->getBaseUrl(), $client);
        $location->setCoordinate($this->getCoordinate());
        $response = $location->call();
        $this->assertCount(0, $response);
    }
}

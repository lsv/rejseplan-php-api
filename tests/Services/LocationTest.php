<?php
namespace RejseplanApiTest\Services;

use RejseplanApi\Services\Location;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class LocationTest extends AbstractServicesTest
{

    public function test_url_setInput()
    {
        $location = new Location($this->getBaseUrl());
        $location->setInput('my input');
        $uri = $location->getRequest()->getUri();
        $this->assertEquals('/location', $uri->getPath());

        parse_str($uri->getQuery(), $query);
        $this->assertEquals('json', $query['format']);
        $this->assertEquals('my input', $query['input']);

        $location = new Location($this->getBaseUrl());
        $location->setInput("my random input\nwith fünny letters\nand breaks");
        $uri = $location->getRequest()->getUri();
        parse_str($uri->getQuery(), $query);
        $this->assertEquals("my random input\nwith fünny letters\nand breaks", $query['input']);
    }

    public function test_not_configured_correct()
    {
        $this->setExpectedException(MissingOptionsException::class, 'The required option "input" is missing.');
        $location = new Location($this->getBaseUrl());
        $location->call();
    }

    public function test_single()
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/location_single.json'));
        $location = new Location($this->getBaseUrl(), $client);
        $location->setInput('my input');
        $response = $location->call();

        $this->assertCount(1, $response);
        $loc1 = $response[0];

        $this->assertEquals('Tivoli Hotel (Kalvebod Brygge)', $loc1->getName());
        $this->assertEquals('000001321', $loc1->getId());
        $this->assertEquals('12.566461', $loc1->getCoordinate()->getLatitude());
        $this->assertEquals('55.665476', $loc1->getCoordinate()->getLongitude());
        $this->assertEquals('12.566461,55.665476', $loc1->getCoordinate());
        $this->assertFalse($loc1->isPoi());
        $this->assertFalse($loc1->isAddress());
        $this->assertTrue($loc1->isStop());

    }

    public function test_response()
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/location.json'));
        $location = new Location($this->getBaseUrl(), $client);
        $location->setInput('my input');
        $response = $location->call();

        $this->assertCount(14, $response);
        $loc1 = $response[1];
        $loc2 = $response[4];
        $loc3 = $response[10];

        $this->assertEquals('Hovedbanegården, Tivoli (Bernstorffsgade)', $loc1->getName());
        $this->assertEquals('000010845', $loc1->getId());
        $this->assertEquals('12.566488', $loc1->getCoordinate()->getLatitude());
        $this->assertEquals('55.672578', $loc1->getCoordinate()->getLongitude());
        $this->assertEquals('12.566488,55.672578', $loc1->getCoordinate());
        $this->assertFalse($loc1->isPoi());
        $this->assertFalse($loc1->isAddress());
        $this->assertTrue($loc1->isStop());

        $this->assertEquals('Thyrasvej/Holtbjerg Aktivcenter', $loc2->getName());
        $this->assertEquals('8.996683', $loc2->getCoordinate()->getLatitude());
        $this->assertEquals('56.132493', $loc2->getCoordinate()->getLongitude());
        $this->assertTrue($loc2->isStop());
        $this->assertFalse($loc2->isAddress());
        $this->assertFalse($loc2->isPoi());

        $this->assertEquals('Tivoli Friheden, Forlystelsespark, Aarhus', $loc3->getName());
        $this->assertEquals('10.198298', $loc3->getCoordinate()->getLatitude());
        $this->assertEquals('56.136619', $loc3->getCoordinate()->getLongitude());
        $this->assertFalse($loc3->isStop());
        $this->assertFalse($loc3->isAddress());
        $this->assertTrue($loc3->isPoi());

    }

}

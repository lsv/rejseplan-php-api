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

    public function test_response()
    {
        $client = $this->getClientWithMock(file_get_contents(__DIR__ . '/mocks/location.json'));
        $location = new Location($this->getBaseUrl(), $client);
        $location->setInput('my input');
        $response = $location->call();

        $this->assertCount(15, $response);
        $loc1 = $response[1];
        $loc2 = $response[5];
        $loc3 = $response[10];

        $this->assertEquals('Hovedbanegården, Tivoli (Bernstorffsgade)', $loc1->getName());
        $this->assertEquals('000010845', $loc1->getId());
        $this->assertEquals('12.566488', $loc1->getCoordinate()->getXCoordinate());
        $this->assertEquals('55.672578', $loc1->getCoordinate()->getYCoordinate());
        $this->assertEquals('12.566488,55.672578', $loc1->getCoordinate());
        $this->assertFalse($loc1->isPOI());
        $this->assertFalse($loc1->isAddress());
        $this->assertTrue($loc1->isStop());

        $this->assertEquals('Ring Syd 3650 Ølstykke, Egedal Kommune', $loc2->getName());
        $this->assertEquals('12.183161', $loc2->getCoordinate()->getXCoordinate());
        $this->assertEquals('55.777815', $loc2->getCoordinate()->getYCoordinate());
        $this->assertFalse($loc2->isStop());
        $this->assertTrue($loc2->isAddress());
        $this->assertFalse($loc2->isPOI());

        $this->assertEquals('Tivoli Hotel, Hotel, København', $loc3->getName());
        $this->assertEquals('12.567135', $loc3->getCoordinate()->getXCoordinate());
        $this->assertEquals('55.666034', $loc3->getCoordinate()->getYCoordinate());
        $this->assertFalse($loc3->isStop());
        $this->assertFalse($loc3->isAddress());
        $this->assertTrue($loc3->isPOI());

    }

}

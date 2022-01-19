<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest;

use DateTime;
use Lsv\Rejseplan\ArrivalBoard;
use Lsv\Rejseplan\Response\Location\Stop;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ArrivalBoardTest extends AbstractTest
{
    /**
     * @test
     */
    public function canGetMultipleArrivals(): void
    {
        $this->setClient(__DIR__.'/stubs/arrivalboard.json');
        $board = new ArrivalBoard('123');
        $response = $board->request();
        $this->assertInstanceOf(RequestInterface::class, $board->getRequest());
        $this->assertInstanceOf(ResponseInterface::class, $board->getResponse());

        $this->assertCount(20, $response->arrivals);
    }

    /**
     * @test
     */
    public function canGetSingleArrival(): void
    {
        $this->setClient(__DIR__.'/stubs/arrivalboard_single.json');
        $board = new ArrivalBoard('123');
        $response = $board->request();

        $this->assertCount(1, $response->arrivals);

        $this->assertSame('RE 1065', $response->arrivals[0]->name);
        $this->assertSame('TOG', $response->arrivals[0]->type);
        $this->assertSame('København H', $response->arrivals[0]->stop);
        $this->assertSame('2016-09-09 14:48', $response->arrivals[0]->scheduledDate->format('Y-m-d H:i'));
        $this->assertTrue($response->arrivals[0]->hasMessages);
        $this->assertSame('1', $response->arrivals[0]->scheduledTrack);
        $this->assertSame('2016-09-09 14:59', $response->arrivals[0]->realtimeDate->format('Y-m-d H:i'));
        $this->assertSame('1', $response->arrivals[0]->realtimeTrack);
        $this->assertSame('Kalmar C', $response->arrivals[0]->origin);
        $this->assertSame('http://baseurl/journeyDetail?ref=3849%2F31310%2F372456%2F184946%2F86%3Fdate%3D09.09.16%26format%3Djson%26', $response->arrivals[0]->journeyDetails);
    }

    /**
     * @test
     */
    public function canSetUrlParameters(): void
    {
        $this->setClient(__DIR__.'/stubs/arrivalboard_single.json');
        $board = new ArrivalBoard('123');
        $board
            ->setDate(new DateTime('2019-05-23 14:22'))
            ->setDontUseBus()
            ->setDontUseMetro()
            ->setDontUseTrain()
            ->request();

        $request = $board->getRequest();
        self::assertSame('/bin/rest.exe/arrivalBoard', $request->getUri()->getPath());
        self::assertSame('format=json&date=23.05.19&useBus=0&useMetro=0&useTog=0&time=14%3A22&id=123', $request->getUri()->getQuery());

        $query = $board->getQuery();
        $this->assertFalse($query['useTog']);
        $this->assertFalse($query['useBus']);
        $this->assertFalse($query['useMetro']);
        $this->assertSame('23.05.19', $query['date']);
        $this->assertSame('14:22', $query['time']);
    }

    /**
     * @test
     */
    public function canGetBoardViaStop(): void
    {
        $this->setClient(__DIR__.'/stubs/arrivalboard_single.json');

        $location = new Stop();
        $location->id = '00012992';

        $board = new ArrivalBoard($location);
        $board->request();
        $query = $board->getQuery();

        $request = $board->getRequest();
        self::assertTrue($request->hasHeader('Accept'));
        self::assertTrue($request->hasHeader('User-Agent'));

        $this->assertSame('00012992', $query['id']);
    }
}

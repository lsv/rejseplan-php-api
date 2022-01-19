<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest;

use Lsv\Rejseplan\Journey;
use Lsv\Rejseplan\Response\Board\BoardData;

class JourneyTest extends AbstractTest
{
    /**
     * @test
     */
    public function canGetJourneyWithMultipleStops(): void
    {
        $this->setClient(__DIR__.'/stubs/journey.json');

        $journey = new Journey('123');
        $response = $journey->request();

        $this->assertSame('Re 4557', $response->name);
        $this->assertSame('REG', $response->type);
        $this->assertCount(14, $response->stops);
        $this->assertCount(3, $response->notes);
        $this->assertCount(2, $response->messages);

        $stop = $response->stops[8];

        $this->assertSame('Roskilde St.', $stop->name);
        $this->assertSame(55.639093, $stop->coordinate->latitude);
        $this->assertSame('8', $stop->routeIdx);
        $this->assertSame('2016-09-09 15:23', $stop->scheduledArrival->format('Y-m-d H:i'));
        $this->assertSame('2016-09-09 15:30', $stop->realtimeArrival->format('Y-m-d H:i'));
        $this->assertSame('2016-09-09 15:24', $stop->scheduledDeparture->format('Y-m-d H:i'));
        $this->assertSame('2016-09-09 15:29', $stop->realtimeDeparture->format('Y-m-d H:i'));
        $this->assertSame('1', $stop->scheduledTrack);
        $this->assertSame('2', $stop->realtimeTrack);
        $this->assertTrue($stop->isTrackChanged(), 'track');
        $this->assertTrue($stop->isArrivalDelayed(), 'arrival');
        $this->assertTrue($stop->isDepartureDelayed(), 'departure');

        $note = $response->notes[1];

        $this->assertSame('kører:ma - fr', $note->text);

        $message = $response->messages[1];

        $this->assertSame('Stoppestedsflytning Astrupvej', $message->header);
        $this->assertStringStartsWith('Stoppestedet Astrupvej for bus linje 2A, 5A og 81N mod København flyttes', $message->text);
    }

    /**
     * @test
     */
    public function canGetJourneyWithSingleMessage(): void
    {
        $this->setClient(__DIR__.'/stubs/journey_singlemessage.json');
        $journey = new Journey('123');
        $response = $journey->request();

        $this->assertCount(1, $response->messages);
        $this->assertSame('Linje 1A kører anden rute i City', $response->messages[0]->header);
        $this->assertStringStartsWith('Linje 1A bliver delt op i to ruter. Del 1 kører mellem Svanemøllen st. og Klampenborg og del 2 kører mellem Avedøre og Valby st', $response->messages[0]->text);
        $this->assertCount(1, $response->notes);
        $this->assertSame('kører:lø, sø', $response->notes[0]->text);
    }

    /**
     * @test
     */
    public function canGetJourneyWithSingleStop(): void
    {
        $this->setClient(__DIR__.'/stubs/journey_singlestop.json');
        $journey = new Journey('123');
        $response = $journey->request();

        $this->assertCount(1, $response->stops);
        $this->assertCount(0, $response->notes);
        $this->assertCount(0, $response->messages);
    }

    /**
     * @test
     */
    public function canSetJourneyFromBoarddata(): void
    {
        $class = new class() extends BoardData {
        };
        $class->journeyDetails = 'https://journey.com/journeydetails?id=2';

        $this->setClient(__DIR__.'/stubs/journey_singlestop.json');

        $journey = new Journey($class);
        $journey->request();
        $details = $journey->getQuery();

        $this->assertSame('https://journey.com/journeydetails?id=2', $details['ref']);
    }
}

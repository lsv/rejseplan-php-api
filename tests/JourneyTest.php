<?php

declare(strict_types=1);

namespace Lsv\RejseplanTest;

use Lsv\Rejseplan\Journey;
use Lsv\Rejseplan\Response\Board\BoardData;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class JourneyTest extends TestCase
{
    /**
     * @test
     */
    public function can_get_journey_with_multiple_stops(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/journey.json')),
        ]);

        $journey = new Journey($client);
        $response = $journey->request('123');

        $this->assertSame('Re 4557', $response->getName());
        $this->assertSame('REG', $response->getType());
        $this->assertCount(14, $response->getStops());
        $this->assertCount(3, $response->getNotes());
        $this->assertCount(2, $response->getMessages());

        $stop = $response->stops[8];

        $this->assertSame('Roskilde St.', $stop->getName());
        $this->assertSame(55.639093, $stop->coordinate->latitude);
        $this->assertSame(8, $stop->index);
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
    public function can_get_journey_with_single_message(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/journey_singlemessage.json')),
        ]);

        $journey = new Journey($client);
        $response = $journey->request('123');

        $this->assertCount(1, $response->messages);
        $this->assertSame('Linje 1A kører anden rute i City', $response->getMessages()[0]->getHeader());
        $this->assertStringStartsWith('Linje 1A bliver delt op i to ruter. Del 1 kører mellem Svanemøllen st. og Klampenborg og del 2 kører mellem Avedøre og Valby st', $response->getMessages()[0]->getText());
        $this->assertCount(1, $response->notes);
        $this->assertSame('kører:lø, sø', $response->getNotes()[0]->getText());
    }

    /**
     * @test
     */
    public function can_get_journey_with_single_stop(): void
    {
        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/journey_singlestop.json')),
        ]);

        $journey = new Journey($client);
        $response = $journey->request('123');

        $this->assertCount(1, $response->stops);
        $this->assertCount(0, $response->notes);
        $this->assertCount(0, $response->messages);
    }

    /**
     * @test
     */
    public function can_set_journey_from_boarddata(): void
    {
        $class = new class() extends BoardData {
        };
        $class->journeyDetails = 'http://journey.com/journeydetails?id=2';

        $client = new MockHttpClient([
            new MockResponse(file_get_contents(__DIR__
                .'/stubs/journey_singlestop.json')),
        ]);

        $journey = new Journey($client);
        $journey->request($class);
        $details = $journey->getQuery();

        $this->assertSame('http://journey.com/journeydetails?id=2', $details['ref']);
    }
}

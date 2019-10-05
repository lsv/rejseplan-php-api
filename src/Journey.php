<?php

declare(strict_types=1);

namespace Lsv\Rejseplan;

use Lsv\Rejseplan\Response\Board\BoardData;
use Lsv\Rejseplan\Response\JourneyResponse;
use Lsv\Rejseplan\Response\Trip\Leg;
use Lsv\Rejseplan\Traits\Coordinate;
use Lsv\Rejseplan\Utils\Parser;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Journey extends Request
{
    use Coordinate;

    /**
     * @param string|BoardData|Leg $journeyUrl
     *
     * @return JourneyResponse
     */
    public function request($journeyUrl): JourneyResponse
    {
        $this->setJourneyUrl($journeyUrl);

        return $this->makeRequest();
    }

    /**
     * @param string|BoardData|Leg $journeyUrl
     *
     * @return Journey
     */
    public function setJourneyUrl($journeyUrl): self
    {
        if ($journeyUrl instanceof BoardData || $journeyUrl instanceof Leg) {
            $journeyUrl = $journeyUrl->getJourney();
        }

        $this->options['ref'] = $journeyUrl;

        return $this;
    }

    protected function configure(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['ref']);
    }

    protected function getUrl(): string
    {
        return 'journeyDetails';
    }

    protected function getResponseClass(): string
    {
        return JourneyResponse::class;
    }

    protected function getResponse(string $response): string
    {
        $details = json_decode($response, true, 512, JSON_THROW_ON_ERROR)['JourneyDetail'];
        $stops = $details['Stop'];
        if (isset($stops['name'])) {
            $stops = [$stops];
        }

        $name = $details['JourneyName']['name'];
        $type = $details['JourneyType']['type'];

        $notes = [];
        if (isset($details['Note'])) {
            $notes = Parser::parseNotes($details['Note']);
        }

        $messages = [];
        if (isset($details['MessageList'])) {
            $messages = Parser::parseMessages($details['MessageList']);
        }

        $json = [
            'stops' => $this->makeCoordinate($stops),
            'name' => $name,
            'type' => $type,
            'notes' => $notes,
            'messages' => $messages,
        ];

        return json_encode($json, JSON_THROW_ON_ERROR);
    }
}

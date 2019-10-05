<?php

declare(strict_types=1);

namespace Lsv\Rejseplan;

use DateTime;
use DateTimeInterface;
use Lsv\Rejseplan\Response\CoordinateResponse;
use Lsv\Rejseplan\Response\Location\Stop;
use Lsv\Rejseplan\Response\TripResponse;
use Lsv\Rejseplan\Utils\LocationParser;
use Lsv\Rejseplan\Utils\Parser;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Trip extends Request
{
    /**
     * @param string|int|Stop|CoordinateResponse $origin
     * @param string|int|Stop|CoordinateResponse $destination
     *
     * @return TripResponse
     */
    public function request($origin, $destination): TripResponse
    {
        $this->options['origin'] = $origin;
        $this->options['dest'] = $destination;

        return $this->makeRequest();
    }

    /**
     * Set via from a location.
     *
     * @param string|int|Stop $viaLocation
     *
     * @return Trip
     */
    public function setVia($viaLocation): self
    {
        $this->options['via'] = $viaLocation;

        return $this;
    }

    /**
     * Set date for the trip.
     *
     * @param DateTime $time Date of the trip
     *
     * @return $this
     */
    public function setDate(DateTime $time): self
    {
        $this->options['date'] = $time;

        return $this;
    }

    /**
     * Do not use busses for this trip.
     *
     * @return $this
     */
    public function setDontUseBus(): self
    {
        $this->options['useBus'] = false;

        return $this;
    }

    /**
     * Do not use trains for this trip.
     *
     * @return $this
     */
    public function setDontUseTrain(): self
    {
        $this->options['useTog'] = false;

        return $this;
    }

    /**
     * Dont use metro for this trip.
     *
     * @return $this
     */
    public function setDontUseMetro(): self
    {
        $this->options['useMetro'] = false;

        return $this;
    }

    /**
     * Set walking distances in meters.
     *
     * @param int $originMaxDistance      Walking distance at origin, in meters, min 500, max 20.000
     * @param int $destinationMaxDistance Walking distance at destination, in meters, min 500, max 20.000
     *
     * @return $this
     */
    public function setWalkingDistance(int $originMaxDistance = 2000, int $destinationMaxDistance = 2000): self
    {
        $this->options['maxWalkingDistanceDep'] = $originMaxDistance;
        $this->options['maxWalkingDistanceDest'] = $destinationMaxDistance;

        return $this;
    }

    /**
     * If you want to ride/have your bike with you.
     *
     * @param int $originMaxDistance      Cycling distance at origin, in meters, min 500, max 20.000
     * @param int $destinationMaxDistance Cycling distance at destination, in meters, min 500, max 20.000
     *
     * @return $this
     */
    public function setBicycleDistance(int $originMaxDistance = 5000, int $destinationMaxDistance = 5000): self
    {
        $this->options['useBicycle'] = true;
        $this->options['maxCyclingDistanceDep'] = $originMaxDistance;
        $this->options['maxCyclingDistanceDest'] = $destinationMaxDistance;

        return $this;
    }

    protected function configure(OptionsResolver $resolver): void
    {
        $distanceAllowedValues = static function ($value) {
            return !($value < 500 || $value > 20000);
        };

        $resolver->setRequired(['origin', 'dest']);
        $resolver->setDefined(
            [
                'via',
                'useTog',
                'useBus',
                'useMetro',
                'useBicycle',
                'maxWalkingDistanceDep',
                'maxWalkingDistanceDest',
                'maxCyclingDistanceDep',
                'maxCyclingDistanceDest',
                'date',
            ]
        );
        $resolver->setAllowedTypes('origin', LocationParser::supports());
        $resolver->setAllowedTypes('dest', LocationParser::supports());
        $resolver->setAllowedTypes('via', LocationParser::supports(false));
        $resolver->setAllowedTypes('useTog', 'bool');
        $resolver->setAllowedTypes('useBus', 'bool');
        $resolver->setAllowedTypes('useMetro', 'bool');
        $resolver->setAllowedTypes('useBicycle', 'bool');
        $resolver->setAllowedValues('maxWalkingDistanceDep', $distanceAllowedValues);
        $resolver->setAllowedValues('maxWalkingDistanceDest', $distanceAllowedValues);
        $resolver->setAllowedValues('maxCyclingDistanceDep', $distanceAllowedValues);
        $resolver->setAllowedValues('maxCyclingDistanceDest', $distanceAllowedValues);
        $resolver->setAllowedTypes('date', DateTime::class);
    }

    protected function getUrl(): string
    {
        return 'trip';
    }

    protected function getResponseClass(): string
    {
        return TripResponse::class;
    }

    protected function getResponse(string $response): string
    {
        $details = json_decode($response, true, 512, JSON_THROW_ON_ERROR)['TripList']['Trip'];

        $trips = [];
        foreach ($details as $detail) {
            $legs = [];
            if (!isset($detail['Leg'])) {
                $detail['Leg'] = $detail;
            }

            if (isset($detail['Leg']['name'])) {
                $detail['Leg'] = [$detail['Leg']];
            }

            foreach ($detail['Leg'] as $leg) {
                if (isset($leg['Notes'])) {
                    $leg['notes'] = Parser::parseNotes($leg['Notes']);
                    unset($leg['Notes']);
                }

                if (isset($leg['MessageList'])) {
                    $leg['messages'] = Parser::parseMessages($leg['MessageList']);
                    unset($leg['MessageList']);
                }

                $leg['origin'] = $leg['Origin'];
                unset($leg['Origin']);

                $leg['destination'] = $leg['Destination'];
                unset($leg['Destination']);

                $legs[] = $leg;
            }

            $trips['trips'][]['legs'] = $legs;
        }

        return json_encode($trips, JSON_THROW_ON_ERROR);
    }

    public function getQuery(): array
    {
        if (
            isset($this->resolvedOptions['date'])
            && ($date = $this->resolvedOptions['date'])
            && $date instanceof DateTimeInterface
        ) {
            $this->resolvedOptions['date'] = $date->format('d.m.y');
            $this->resolvedOptions['time'] = $date->format('H:i');
        }

        LocationParser::parse($this->resolvedOptions, 'origin');
        LocationParser::parse($this->resolvedOptions, 'dest');
        LocationParser::parse($this->resolvedOptions, 'via');

        return parent::getQuery();
    }
}

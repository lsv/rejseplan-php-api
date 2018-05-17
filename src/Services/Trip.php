<?php

namespace RejseplanApi\Services;

use Psr\Http\Message\ResponseInterface;
use RejseplanApi\Services\Response\LocationResponse;
use RejseplanApi\Services\Response\StopLocationResponse;
use RejseplanApi\Services\Response\TripResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This calculates a trip from a specified origin to a specified destination. These
 * might be stop/station IDs or coordinates based on addresses and points of interest validated
 * by the location service or coordinates freely defined by the client.
 */
class Trip extends AbstractServiceCall
{
    /**
     * Set origin from a location.
     *
     * @param LocationResponse|StopLocationResponse $location Origin location
     *
     * @return $this
     */
    public function setOrigin($location): self
    {
        $this->options['origin'] = $location;

        return $this;
    }

    /**
     * Set destination from a location.
     *
     * @param LocationResponse|StopLocationResponse $location Destination location
     *
     * @return $this
     */
    public function setDestination($location): self
    {
        $this->options['dest'] = $location;

        return $this;
    }

    /**
     * Set via from a location.
     *
     * @param LocationResponse|StopLocationResponse $location Via location
     *
     * @return $this
     */
    public function setVia($location): self
    {
        $this->options['via'] = $location;

        return $this;
    }

    /**
     * Set date for the trip.
     *
     * @param \DateTime $time Date of the trip
     *
     * @return $this
     */
    public function setDate(\DateTime $time): self
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
        $this->options['useBus'] = 0;

        return $this;
    }

    /**
     * Do not use trains for this trip.
     *
     * @return $this
     */
    public function setDontUseTrain(): self
    {
        $this->options['useTog'] = 0;

        return $this;
    }

    /**
     * Dont use metro for this trip.
     *
     * @return $this
     */
    public function setDontUseMetro(): self
    {
        $this->options['useMetro'] = 0;

        return $this;
    }

    /**
     * Set walking distances in meters.
     *
     * @param int $originDistance      Walking distance at origin, in meters, min 500, max 20.000
     * @param int $destinationDistance Walking distance at destination, in meters, min 500, max 20.000
     *
     * @return $this
     */
    public function setWalkingDistance(int $originDistance = 2000, int $destinationDistance = 2000): self
    {
        $this->options['maxWalkingDistanceDep'] = $originDistance;
        $this->options['maxWalkingDistanceDest'] = $destinationDistance;

        return $this;
    }

    /**
     * If you want to ride/have your bike with you.
     *
     * @param int $originDistance      Cycling distance at origin, in meters, min 500, max 20.000
     * @param int $destinationDistance Cycling distance at destination, in meters, min 500, max 20.000
     *
     * @return $this
     */
    public function setUseBicycle(int $originDistance = 5000, int $destinationDistance = 5000): self
    {
        $this->options['useBicycle'] = 1;
        $this->options['maxCyclingDistanceDep'] = $originDistance;
        $this->options['maxCyclingDistanceDest'] = $destinationDistance;

        return $this;
    }

    /**
     * Configure the options.
     *
     * @param OptionsResolver $options
     */
    protected function configureOptions(OptionsResolver $options): void
    {
        $distanceAllowedValues = function ($value) {
            return !($value < 500 || $value > 20000);
        };

        $options->setRequired(['origin', 'dest']);

        $options->setDefined([
            'via',
            'useTog', 'useBus', 'useMetro', 'useBicycle',
            'maxWalkingDistanceDep', 'maxWalkingDistanceDest',
            'maxCyclingDistanceDep', 'maxCyclingDistanceDest',
            'date',
        ]);

        $options->setAllowedTypes('origin', [LocationResponse::class, StopLocationResponse::class]);
        $options->setAllowedTypes('dest', [LocationResponse::class, StopLocationResponse::class]);
        $options->setAllowedTypes('via', [LocationResponse::class, StopLocationResponse::class]);

        $options->setAllowedValues('useTog', [0, 1]);
        $options->setAllowedValues('useBus', [0, 1]);
        $options->setAllowedValues('useMetro', [0, 1]);
        $options->setAllowedValues('useBicycle', [0, 1]);

        $options->setAllowedValues('maxWalkingDistanceDep', $distanceAllowedValues);
        $options->setAllowedValues('maxWalkingDistanceDest', $distanceAllowedValues);
        $options->setAllowedValues('maxCyclingDistanceDep', $distanceAllowedValues);
        $options->setAllowedValues('maxCyclingDistanceDest', $distanceAllowedValues);

        $options->setAllowedTypes('date', \DateTime::class);
    }

    /**
     * Create the URL.
     *
     * @param array $options
     *
     * @return string
     */
    protected function getUrl(array $options): string
    {
        $urlOptions = [];
        $this->setOriginAndDestinationOption($urlOptions, $options);
        $this->setViaOption($urlOptions, $options);
        $this->setDateOption($urlOptions, $options);

        $urlOptions = array_merge($urlOptions, $options);

        return sprintf('trip?%s&format=json', http_build_query($urlOptions));
    }

    /**
     * Generate the response object.
     *
     * @param ResponseInterface $response
     *
     * @return TripResponse[]
     */
    protected function generateResponse(ResponseInterface $response): array
    {
        $json = $this->validateJson($response);

        $trips = [];
        if (!isset($json['TripList']['Trip'])) {
            return $trips;
        }

        foreach ($json['TripList']['Trip'] as $trip) {
            $trips[] = TripResponse::createFromArray($trip);
        }

        return $trips;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return TripResponse[]
     */
    public function call(): array
    {
        return $this->doCall();
    }

    private function setDateOption(array &$urlOptions, array &$options): void
    {
        if (isset($options['date'])) {
            $urlOptions['date'] = $options['date']->format('d.m.y');
            $urlOptions['time'] = $options['date']->format('H:i');
            unset($options['date']);
        }
    }

    private function setViaOption(array &$urlOptions, array &$options): void
    {
        if (isset($options['via'])) {
            $via = $options['via'];
            if ($via instanceof LocationResponse) {
                if (!$via->isStop()) {
                    throw new \InvalidArgumentException('Via is not a stop location');
                }

                $urlOptions['via'] = $via->getId();
                unset($options['via']);

                return;
            }

            $urlOptions['via'] = $via->getId();
            unset($options['via']);

            return;
        }
    }

    private function setOriginAndDestinationOption(array &$urlOptions, array &$options): void
    {
        $keys = ['origin', 'dest'];
        foreach ($keys as $key) {
            /** @var LocationResponse $option */
            $option = $options[$key];
            $urlOptions[$key . 'Id'] = $option->getId();
            if ($option instanceof LocationResponse) {
                if ($option->isStop()) {
                    $urlOptions[$key . 'Id'] = $option->getId();
                } else {
                    $urlOptions[$key . 'CoordName'] = $option->getName();
                    if ($option->getCoordinate()) {
                        $urlOptions[$key . 'CoordX'] = $option->getCoordinate()->getLatitude();
                        $urlOptions[$key . 'CoordY'] = $option->getCoordinate()->getLongitude();
                    }
                }
            }
        }

        unset($options['origin'], $options['dest']);
    }
}

<?php

declare(strict_types=1);

namespace Lsv\Rejseplan;

use Lsv\Rejseplan\Response\NearbyStopsResponse;
use Lsv\Rejseplan\Traits\Coordinate;
use Lsv\Rejseplan\Utils\LocationParser;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NearbyStops extends Request
{
    use Coordinate;

    public function request(float $latitude, float $longitude): NearbyStopsResponse
    {
        $this->options['coordY'] = $latitude;
        $this->options['coordX'] = $longitude;

        return $this->makeRequest();
    }

    /**
     * Set radius to get the locations.
     *
     * @param int $meters
     *
     * @return $this
     */
    public function setRadius(int $meters = 1000): self
    {
        $this->options['maxRadius'] = $meters;

        return $this;
    }

    /**
     * Set max number of results.
     *
     * @param int $results
     *
     * @return $this
     */
    public function setMaxResults(int $results = 30): self
    {
        $this->options['maxNumber'] = $results;

        return $this;
    }

    protected function configure(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['coordX', 'coordY', 'maxRadius', 'maxNumber']);
        $resolver->setAllowedTypes('coordX', ['float']);
        $resolver->setAllowedTypes('coordY', ['float']);
        $resolver->setDefault('maxRadius', 1000);
        $resolver->setAllowedTypes('maxRadius', ['int']);
        $resolver->setDefault('maxNumber', 30);
        $resolver->setAllowedTypes('maxNumber', ['int']);

        $coord = static function (
            /* @noinspection PhpUnusedParameterInspection */
            Options $options,
            $value
        ) {
            return LocationParser::coordinateToNumber($value);
        };

        $resolver->setNormalizer('coordX', $coord);
        $resolver->setNormalizer('coordY', $coord);
    }

    protected function getUrl(): string
    {
        return 'stopsNearby';
    }

    protected function getResponseClass(): string
    {
        return NearbyStopsResponse::class;
    }

    protected function getResponse(string $response): string
    {
        $stops = json_decode($response, true, 512, JSON_THROW_ON_ERROR)['LocationList']['StopLocation'];
        if (isset($stops['name'])) {
            $stops = [$stops];
        }

        return json_encode([
            'stops' => $this->makeCoordinate($stops),
        ], JSON_THROW_ON_ERROR);
    }
}

<?php

declare(strict_types=1);

namespace Lsv\Rejseplan;

use Lsv\Rejseplan\Response\LocationsResponse;
use Lsv\Rejseplan\Traits\Coordinate;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Location extends AbstractRequest
{
    use Coordinate;

    public function __construct(string $search)
    {
        $this->options['input'] = $search;
    }

    public function request(): LocationsResponse
    {
        return $this->makeRequest();
    }

    public function doNotIncludeStops(): self
    {
        $this->options['include_stops'] = false;

        return $this;
    }

    public function doNotIncludeAddresses(): self
    {
        $this->options['include_addresses'] = false;

        return $this;
    }

    public function doNotIncludePOI(): self
    {
        $this->options['include_pois'] = false;

        return $this;
    }

    protected function configure(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['input', 'include_stops', 'include_addresses', 'include_pois']);
        $resolver->setDefault('include_stops', true);
        $resolver->setDefault('include_addresses', true);
        $resolver->setDefault('include_pois', true);

        $resolver->setAllowedTypes('input', ['string', 'int', 'float']);
        $resolver->setAllowedTypes('include_stops', 'bool');
        $resolver->setAllowedTypes('include_addresses', 'bool');
        $resolver->setAllowedTypes('include_pois', 'bool');
    }

    protected function getUrl(): string
    {
        return 'location';
    }

    protected function getResponseClass(): string
    {
        return LocationsResponse::class;
    }

    protected function makeResponse(string $response): string
    {
        $details = json_decode($response, true, flags: JSON_THROW_ON_ERROR)['LocationList'];

        $stops = [];
        if (isset($details['StopLocation'])) {
            $stops = $details['StopLocation'];
            if (isset($stops['name'])) {
                $stops = [$stops];
            }
        }

        $pois = [];
        $addresses = [];

        if (isset($details['CoordLocation'])) {
            $coords = $details['CoordLocation'];
            if (isset($coords['name'])) {
                $coords = [$coords];
            }

            $pois = array_values(array_filter($coords, static fn ($coord) => 'POI' === $coord['type']));
            $addresses = array_values(array_filter($coords, static fn ($coord) => 'ADR' === $coord['type']));
        }

        $json = [
            'stops' => $this->makeCoordinate($stops),
            'pois' => $this->makeCoordinate($pois),
            'addresses' => $this->makeCoordinate($addresses),
        ];

        return json_encode($json, JSON_THROW_ON_ERROR);
    }
}

<?php

namespace RejseplanApi\Services;

use Psr\Http\Message\ResponseInterface;
use RejseplanApi\Services\Response\StopLocationResponse;
use RejseplanApi\Utils\Coordinate;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This will deliver all stops within a radius of a given coordinate.
 */
class NearbyStops extends AbstractServiceCall
{
    /**
     * Set coordinate to get nearby stops.
     *
     * @param Coordinate $coordinate
     *
     * @return $this
     */
    public function setCoordinate(Coordinate $coordinate): self
    {
        $this->options['coordX'] = $coordinate->getLatitude();
        $this->options['coordY'] = $coordinate->getLongitude();

        return $this;
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

    /**
     * Configure the options.
     *
     * @param OptionsResolver $options
     */
    protected function configureOptions(OptionsResolver $options): void
    {
        $options->setRequired(['coordX', 'coordY', 'maxRadius', 'maxNumber']);
        $options->setDefault('maxRadius', 1000);
        $options->setDefault('maxNumber', 30);

        $options->setAllowedTypes('coordX', ['float']);
        $options->setAllowedTypes('coordY', ['float']);
        $options->setAllowedTypes('maxRadius', ['int']);

        $options->setNormalizer('coordX', function (
            /* @noinspection PhpUnusedParameterInspection */
            Options $options,
            $value
        ) {
            return $value * 1000000;
        });

        $options->setNormalizer('coordY', function (
            /* @noinspection PhpUnusedParameterInspection */
            Options $options,
            $value
        ) {
            return $value * 1000000;
        });
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
        return sprintf('stopsNearby?%s&format=json', http_build_query($options));
    }

    /**
     * Generate the response object.
     *
     * @param ResponseInterface $response
     *
     * @return StopLocationResponse[]
     */
    protected function generateResponse(ResponseInterface $response): array
    {
        $json = $this->validateJson($response);

        $output = [];
        if (!isset($json['LocationList']['StopLocation'])) {
            return $output;
        }

        $stops = self::checkForSingle($json['LocationList']['StopLocation'], 'name');
        foreach ($stops as $stop) {
            $output[] = StopLocationResponse::createFromArray($stop);
        }

        return $output;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return StopLocationResponse[]
     */
    public function call(): array
    {
        return $this->doCall();
    }
}

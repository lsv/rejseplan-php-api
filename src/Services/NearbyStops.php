<?php

namespace RejseplanApi\Services;

use Psr\Http\Message\ResponseInterface;
use RejseplanApi\Coordinate;
use RejseplanApi\Services\Response\StopLocationResponse;
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
    public function setCoordinate(Coordinate $coordinate)
    {
        $this->options['coordX'] = $coordinate->getXCoordinate();
        $this->options['coordY'] = $coordinate->getYCoordinate();

        return $this;
    }

    /**
     * Set radius to get the locations.
     *
     * @param int $meters
     *
     * @return $this
     */
    public function setRadius($meters = 1000)
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
    public function setMaxResults($results = 30)
    {
        $this->options['maxNumber'] = $results;

        return $this;
    }

    /**
     * Configure the options.
     *
     * @param OptionsResolver $options
     */
    protected function configureOptions(OptionsResolver $options)
    {
        $options->setRequired(['coordX', 'coordY', 'maxRadius', 'maxNumber']);
        $options->setDefault('maxRadius', 1000);
        $options->setDefault('maxNumber', 30);

        $options->setAllowedTypes('coordX', ['float']);
        $options->setAllowedTypes('coordY', ['float']);
        $options->setAllowedTypes('maxRadius', ['int']);

        $options->setNormalizer('coordX', function (Options $options, $value) {
            return $value * 1000000;
        });

        $options->setNormalizer('coordY', function (Options $options, $value) {
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
    protected function getUrl(array $options)
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
    protected function generateResponse(ResponseInterface $response)
    {
        $json = \GuzzleHttp\json_decode((string) $response->getBody(), true);
        $output = [];
        foreach ($json['LocationList']['StopLocation'] as $stop) {
            $output[] = StopLocationResponse::createFromArray($stop);
        }

        return $output;
    }

    /**
     * Call it.
     *
     * @return StopLocationResponse[]
     */
    public function call()
    {
        return $this->doCall();
    }
}

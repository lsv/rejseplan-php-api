<?php

namespace RejseplanApi\Services;

use Psr\Http\Message\ResponseInterface;
use RejseplanApi\Services\Response\LocationResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The location service can be used to perform a pattern matching of a user input and to
 * retrieve a list of possible matches in the journey planner database. Possible matches
 * might be stops/stations, points of interest and addresses.
 */
class Location extends AbstractServiceCall
{
    /**
     * Set user input for a location search.
     *
     * @param string $input user input to find a location, which can be stations, POIs and adresses
     *
     * @return $this
     */
    public function setInput($input)
    {
        $this->options['input'] = $input;

        return $this;
    }

    /**
     * Configure the options.
     *
     * @param OptionsResolver $options
     */
    protected function configureOptions(OptionsResolver $options)
    {
        $options->setRequired(['input']);
        $options->setAllowedTypes('input', ['string', 'int', 'float']);
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
        return sprintf('location?input=%s&format=json', urlencode($options['input']));
    }

    /**
     * Generate the response object.
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    protected function generateResponse(ResponseInterface $response)
    {
        $output = [];
        $data = \GuzzleHttp\json_decode($response->getBody(), true);
        foreach ($data['LocationList'] as $type => $stops) {
            if ($type == 'noNamespaceSchemaLocation') {
                continue;
            }

            foreach ($stops as $stop) {
                $output[] = LocationResponse::createFromArray($stop);
            }
        }

        return $output;
    }

    /**
     * Call it.
     *
     * @return LocationResponse[]
     */
    public function call()
    {
        return $this->doCall();
    }
}

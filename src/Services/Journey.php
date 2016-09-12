<?php
namespace RejseplanApi\Services;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use RejseplanApi\Services\Response\JourneyResponse;
use RejseplanApi\Services\Response\StationBoard\BoardData;
use RejseplanApi\Services\Response\Trip\Leg;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This service will deliver information about the complete route of a vehicle.
 * It contains a list of all stops/stations of this journey including
 * all departure and arrival times (with realtime data if available) and additional information
 * like specific attributes about facilities and other texts.
 */
class Journey extends AbstractServiceCall
{

    /**
     * Set journey details from a URL
     *
     * @param string|Leg|BoardData $url
     * @return $this
     */
    public function setUrl($url)
    {
        if (is_string($url)) {
            $this->options['url'] = $url;
            return $this;
        }

        if ($url instanceof Leg) {
            $this->setUrlFromLeg($url);
            return $this;
        }

        if ($url instanceof BoardData) {
            $this->setUrlFromBoardData($url);
            return $this;
        }

        throw new \InvalidArgumentException(
            sprintf('url must be a string, Leg or BoardData object')
        );
    }

    /**
     * Set journey details from a Leg object
     *
     * @param Leg $leg
     * @return $this
     */
    protected function setUrlFromLeg(Leg $leg)
    {
        $this->options['url'] = $leg->getJournyDetails();
        return $this;
    }

    /**
     * Set journey details from a BoardData object
     *
     * @param BoardData $data
     * @return $this
     */
    protected function setUrlFromBoardData(BoardData $data)
    {
        $this->options['url'] = $data->getJourneyDetails();
        return $this;
    }

    /**
     * Configure the options
     *
     * @param OptionsResolver $options
     */
    protected function configureOptions(OptionsResolver $options)
    {
        $options->setRequired(['url']);
    }

    /**
     * Create the URL
     *
     * @param array $options
     * @return string
     */
    protected function getUrl(array $options)
    {
        $url = parse_url($options['url'], PHP_URL_QUERY);
        parse_str($url, $query);
        if (! isset($query['format'])) {
            return sprintf('%s&format=json', $options['url']);
        }

        return $options['url'];
    }

    /**
     * Generate the response object
     *
     * @param ResponseInterface $response
     * @return JourneyResponse
     */
    protected function generateResponse(ResponseInterface $response)
    {
        $json = \GuzzleHttp\json_decode((string) $response->getBody(), true);
        return JourneyResponse::createFromArray($json['JourneyDetail']);
    }

    /**
     * Call it
     *
     * @return JourneyResponse
     */
    public function call()
    {
        return $this->doCall();
    }

    /**
     * Create the request
     */
    protected function createRequest()
    {
        $this->request = new Request($this->getMethod(), $this->getUrl($this->options), $this->getHeaders());
    }
}

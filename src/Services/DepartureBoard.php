<?php

namespace RejseplanApi\Services;

use Psr\Http\Message\ResponseInterface;
use RejseplanApi\Services\Response\DepartureBoardResponse;
use RejseplanApi\Services\Response\LocationResponse;
use RejseplanApi\Services\Response\StopLocationResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartureBoard extends AbstractServiceCall
{
    /**
     * Station to get the departure board.
     *
     * @param LocationResponse|StopLocationResponse $location
     *
     * @return $this
     */
    public function setLocation($location)
    {
        if ($location instanceof LocationResponse) {
            $this->setLocationResponse($location);

            return $this;
        }

        if ($location instanceof StopLocationResponse) {
            $this->setStopResponse($location);

            return $this;
        }

        throw new \InvalidArgumentException('The location must be either a LocationResponse or a StopLocationResponse object');
    }

    /**
     * Station to get the departure board.
     *
     * @param LocationResponse $location
     *
     * @return $this
     */
    protected function setLocationResponse(LocationResponse $location)
    {
        if (!$location->isStop()) {
            throw new \InvalidArgumentException('The location must be a station');
        }

        $this->options['id'] = $location->getId();

        return $this;
    }

    /**
     * Station to get the departure board.
     *
     * @param StopLocationResponse $stop
     *
     * @return $this
     */
    protected function setStopResponse(StopLocationResponse $stop)
    {
        $this->options['id'] = $stop->getId();

        return $this;
    }

    /**
     * If you dont want to see trains on the station board.
     *
     * @return $this
     */
    public function setDontUseTrain()
    {
        $this->options['useTog'] = 0;

        return $this;
    }

    /**
     * If you dont want to see busses on the station board.
     *
     * @return $this
     */
    public function setDontUseBus()
    {
        $this->options['useBus'] = 0;

        return $this;
    }

    /**
     * If you dont want to see metro on the station board.
     *
     * @return $this
     */
    public function setDontUseMetro()
    {
        $this->options['useMetro'] = 0;

        return $this;
    }

    /**
     * Date off when you want to get the station board.
     *
     * @param \DateTime $date
     *
     * @return $this
     */
    public function setDate(\DateTime $date)
    {
        $this->options['date'] = $date;

        return $this;
    }

    /**
     * Configure the options.
     *
     * @param OptionsResolver $options
     */
    protected function configureOptions(OptionsResolver $options)
    {
        $options->setRequired('id');
        $options->setAllowedTypes('id', ['string']);
        $options->setDefined(['date', 'time', 'useTog', 'useBus', 'useMetro']);

        $options->addAllowedTypes('date', \DateTime::class);
        $options->setAllowedValues('useTog', [0, 1]);
        $options->setAllowedValues('useBus', [0, 1]);
        $options->setAllowedValues('useMetro', [0, 1]);
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
        return sprintf('departureBoard?%s&format=json', http_build_query($this->generateUrlOptions($options)));
    }

    /**
     * Generate url options.
     *
     * @param array $options
     *
     * @return array
     */
    protected function generateUrlOptions(array $options)
    {
        $urlOptions = [];
        if (isset($options['date'])) {
            $urlOptions['date'] = $options['date']->format('d.m.y');
            $urlOptions['time'] = $options['date']->format('H:i');
            unset($options['date']);
        }

        return array_merge($urlOptions, $options);
    }

    /**
     * Generate the response object.
     *
     * @param ResponseInterface $response
     *
     * @return DepartureBoardResponse
     */
    protected function generateResponse(ResponseInterface $response)
    {
        $json = \GuzzleHttp\json_decode((string) $response->getBody(), true);

        return DepartureBoardResponse::createFromArray($json['DepartureBoard']);
    }

    /**
     * Call it.
     *
     * @return DepartureBoardResponse
     */
    public function call()
    {
        return $this->doCall();
    }
}

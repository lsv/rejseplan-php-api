<?php

namespace RejseplanApi\Services;

use Psr\Http\Message\ResponseInterface;
use RejseplanApi\Services\Response\ArrivalBoardResponse;
use RejseplanApi\Services\Response\LocationResponse;
use RejseplanApi\Services\Response\StopLocationResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArrivalBoard extends AbstractServiceCall
{
    /**
     * Station to get the departure board.
     *
     * @param mixed $location
     *
     * @return $this
     */
    public function setLocation($location): self
    {
        if ($location instanceof LocationResponse) {
            $this->setLocationResponse($location);

            return $this;
        }

        if ($location instanceof StopLocationResponse) {
            $this->setStopResponse($location);

            return $this;
        }

        if (\is_int($location) || \is_string($location)) {
            $this->setLocationId($location);

            return $this;
        }

        throw new \InvalidArgumentException(
            'The location must be either a LocationResponse object, StopLocationResponse object, string or integer'
        );
    }

    /**
     * If you dont want to see trains on the station board.
     *
     * @return $this
     */
    public function setDontUseTrain(): self
    {
        $this->options['useTog'] = 0;

        return $this;
    }

    /**
     * If you dont want to see busses on the station board.
     *
     * @return $this
     */
    public function setDontUseBus(): self
    {
        $this->options['useBus'] = 0;

        return $this;
    }

    /**
     * If you dont want to see metro on the station board.
     *
     * @return $this
     */
    public function setDontUseMetro(): self
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
    public function setDate(\DateTime $date): self
    {
        $this->options['date'] = $date;

        return $this;
    }

    /**
     * Station to get the departure board.
     *
     * @param LocationResponse $location
     *
     * @return $this
     */
    protected function setLocationResponse(LocationResponse $location): self
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
    protected function setStopResponse(StopLocationResponse $stop): self
    {
        $this->options['id'] = $stop->getId();

        return $this;
    }

    /**
     * Station ID to get the departure board.
     *
     * @param string|int $id
     *
     * @return $this
     */
    protected function setLocationId($id): self
    {
        $this->options['id'] = (string) $id;

        return $this;
    }

    /**
     * Configure the options.
     *
     * @param OptionsResolver $options
     */
    protected function configureOptions(OptionsResolver $options): void
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
    protected function getUrl(array $options): string
    {
        return sprintf('arrivalBoard?%s&format=json', http_build_query($this->generateUrlOptions($options)));
    }

    /**
     * Generate url options.
     *
     * @param array $options
     *
     * @return array
     */
    protected function generateUrlOptions(array $options): array
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
     * @return ArrivalBoardResponse
     */
    protected function generateResponse(ResponseInterface $response): ArrivalBoardResponse
    {
        $json = $this->validateJson($response);
        if (isset($json['ArrivalBoard'])) {
            return ArrivalBoardResponse::createFromArray($json['ArrivalBoard']);
        }

        return new ArrivalBoardResponse();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return ArrivalBoardResponse
     */
    public function call(): ArrivalBoardResponse
    {
        return $this->doCall();
    }
}

<?php

namespace RejseplanApi\Services;

use Psr\Http\Message\ResponseInterface;
use RejseplanApi\Services\Response\ArrivalBoardResponse;

class ArrivalBoard extends DepartureBoard
{
    /**
     * Create the URL.
     *
     * @param array $options
     *
     * @return string
     */
    protected function getUrl(array $options)
    {
        return sprintf('arrivalBoard?%s&format=json', http_build_query($this->generateUrlOptions($options)));
    }

    /**
     * Generate the response object.
     *
     * @param ResponseInterface $response
     *
     * @return ArrivalBoardResponse
     */
    protected function generateResponse(ResponseInterface $response)
    {
        $json = $this->validateJson($response);
        if (isset($json['ArrivalBoard'])) {
            return ArrivalBoardResponse::createFromArray($json['ArrivalBoard']);
        }

        return new ArrivalBoardResponse();
    }

    /**
     * Call it.
     *
     * @return ArrivalBoardResponse
     */
    public function call()
    {
        return $this->doCall();
    }
}

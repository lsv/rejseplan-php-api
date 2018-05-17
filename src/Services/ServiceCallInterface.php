<?php

namespace RejseplanApi\Services;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ServiceCallInterface
{
    /**
     * Call it.
     *
     * @return mixed
     */
    public function call();/** @noinspection ReturnTypeCanBeDeclaredInspection */

    /**
     * Set the base url for the calls.
     *
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl(string $baseUrl);/** @noinspection ReturnTypeCanBeDeclaredInspection */

    /**
     * Set the client used for the operations.
     *
     * @param ClientInterface|null $client
     *
     * @return $this
     */
    public function setClient(ClientInterface $client = null);

    /**
     * Get the request object.
     */
    public function getRequest(): RequestInterface;

    /**
     * Get the actual response.
     */
    public function getResponse(): ResponseInterface;
}

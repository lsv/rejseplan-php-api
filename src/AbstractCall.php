<?php

namespace RejseplanApi;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractCall
{
    const API_NAME = 'rejseplanphpapi';
    const API_VERSION = '1.0';

    /**
     * Base url.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Client.
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * Options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Request.
     *
     * @var Request
     */
    protected $request;

    /**
     * Response.
     *
     * @var Response
     */
    protected $response;

    /**
     * Configure the options.
     *
     * @param OptionsResolver $options
     */
    abstract protected function configureOptions(OptionsResolver $options);

    /**
     * Create the URL.
     *
     * @param array $options
     *
     * @return string
     */
    abstract protected function getUrl(array $options);

    /**
     * Generate the response object.
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    abstract protected function generateResponse(ResponseInterface $response);

    /**
     * Call it.
     *
     * @return mixed
     */
    abstract public function call();

    /**
     * AbstractCall constructor.
     *
     * @param string               $baseUrl
     * @param ClientInterface|null $client
     */
    public function __construct($baseUrl, ClientInterface $client = null)
    {
        $this->setBaseUrl($baseUrl);
        $this->setClient($client);
    }

    /**
     * Set the base url for the calls.
     *
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');

        return $this;
    }

    /**
     * Set the client used for the operations.
     *
     * @param ClientInterface|null $client
     *
     * @return $this
     */
    public function setClient(ClientInterface $client = null)
    {
        if (null === $client) {
            $this->client = new Client();

            return $this;
        }

        $this->client = $client;

        return $this;
    }

    /**
     * Get the request object.
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the actual response.
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get the HTTP method.
     *
     * @return string
     */
    protected function getMethod()
    {
        return 'GET';
    }

    /**
     * Set the client call headers.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'User-Agent' => sprintf('%s/%s', self::API_NAME, self::API_VERSION),
        ];
    }
}

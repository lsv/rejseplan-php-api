<?php

namespace RejseplanApi;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RejseplanApi\Services\ServiceCallInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractCall implements ServiceCallInterface
{
    private const API_NAME = 'rejseplanphpapi';
    private const API_VERSION = '1.0';
    private const USER_AGENT = self::API_NAME . '/' . self::API_VERSION;

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
     * @var RequestInterface
     */
    protected $request;

    /**
     * Response.
     *
     * @var ResponseInterface
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
    abstract protected function getUrl(array $options): string;

    /**
     * Generate the response object.
     *
     * @param ResponseInterface $response
     *
     * @return mixed
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
    public function __construct($baseUrl, ?ClientInterface $client = null)
    {
        $this->setBaseUrl($baseUrl);
        $this->setClient($client);
    }

    /**
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl(string $baseUrl): self
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
    public function setClient(ClientInterface $client = null): self
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
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * Get the actual response.
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Get the HTTP method.
     *
     * @return string
     */
    protected function getMethod(): string
    {
        return 'GET';
    }

    /**
     * Set the client call headers.
     *
     * @return array
     */
    protected function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'User-Agent' => self::USER_AGENT,
        ];
    }
}

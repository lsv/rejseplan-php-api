<?php

namespace RejseplanApi\Services;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RejseplanApi\AbstractCall;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractServiceCall extends AbstractCall
{
    /**
     * Get the request object.
     */
    public function getRequest(): RequestInterface
    {
        if (!$this->request) {
            $this->resolveOptions();
            $this->request = $this->createRequest();
        }

        return parent::getRequest();
    }

    /**
     * Get the actual response.
     */
    public function getResponse(): ResponseInterface
    {
        if (!$this->response) {
            $this->call();
        }

        return parent::getResponse();
    }

    /**
     * Return the generated response.
     *
     * @throws GuzzleException
     */
    protected function doCall()
    {
        $this->resolveOptions();
        $this->request = $this->createRequest();
        $this->response = $this->client->send($this->request);

        return $this->generateResponse($this->response);
    }

    /**
     * Resolve options.
     */
    protected function resolveOptions(): void
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($this->options);
    }

    /**
     * Create the request.
     */
    protected function createRequest(): RequestInterface
    {
        $url = sprintf('%s/%s', $this->baseUrl, $this->getUrl($this->options));

        return new Request($this->getMethod(), $url, $this->getHeaders());
    }

    /**
     * Validate json.
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    protected function validateJson(ResponseInterface $response): array
    {
        try {
            return \GuzzleHttp\json_decode((string) $response->getBody(), true);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Check if the response is a object, or a array of objects.
     *
     * @param array  $data
     * @param string $key
     *
     * @return array
     */
    public static function checkForSingle(array $data, string $key): array
    {
        if (array_key_exists($key, $data)) {
            return [$data];
        }

        return $data;
    }
}

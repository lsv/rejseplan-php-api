<?php

namespace RejseplanApi\Services;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RejseplanApi\AbstractCall;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractServiceCall extends AbstractCall implements ServiceCallInterface
{
    /**
     * Get the request object.
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        if (!$this->request) {
            $this->resolveOptions();
            $this->createRequest();
        }

        return parent::getRequest();
    }

    /**
     * Get the actual response.
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        if (!$this->response) {
            $this->call();
        }

        return parent::getResponse();
    }

    /**
     * Return the generated response.
     *
     * @return mixed
     */
    protected function doCall()
    {
        $this->resolveOptions();
        $this->createRequest();
        $this->response = $this->client->send($this->request);

        return $this->generateResponse($this->response);
    }

    /**
     * Resolve options.
     */
    protected function resolveOptions()
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $resolver->resolve($this->options);
    }

    /**
     * Create the request.
     */
    protected function createRequest()
    {
        $url = sprintf('%s/%s', $this->baseUrl, $this->getUrl($this->options));
        $this->request = new Request($this->getMethod(), $url, $this->getHeaders());
    }

    /**
     * Validate json
     *
     * @param ResponseInterface $response
     * @return array
     */
    protected function validateJson(ResponseInterface $response)
    {
        try {
            return \GuzzleHttp\json_decode((string) $response->getBody(), true);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Check if the response is a object, or a array of objects
     *
     * @param array $data
     * @param string $key
     * @return array
     */
    public static function checkForSingle(array $data, $key)
    {
        if (array_key_exists($key, $data)) {
            return [$data];
        }

        return $data;
    }
}

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
}

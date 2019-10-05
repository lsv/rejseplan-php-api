<?php

declare(strict_types=1);

namespace Lsv\Rejseplan;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class Request
{
    private const BASE_URL = 'http://xmlopen.rejseplanen.dk/bin/rest.exe/';
    private const API_NAME = 'rejseplan_php_api';
    private const API_VERSION = '2.0';
    private const USER_AGENT = self::API_NAME.'/'.self::API_VERSION;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $resolvedOptions = [];

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct(?HttpClientInterface $client = null)
    {
        $this->setClient($client ?: HttpClient::create());
    }

    public function setClient(HttpClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getQuery(): array
    {
        return $this->resolvedOptions;
    }

    /**
     * @return mixed
     */
    protected function makeRequest()
    {
        $resolver = new OptionsResolver();
        $this->configure($resolver);
        $this->resolvedOptions = $resolver->resolve($this->options);
        $this->response = $this->client->request(
            $this->getMethod(),
            $this->getUrl(),
            $this->getHeaders()
        );

        return $this->getSerializer()->deserialize(
            $this->getResponse($this->response->getContent()),
            $this->getResponseClass(),
            'json'
        );
    }

    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function getHeaders(): array
    {
        return [
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => self::USER_AGENT,
            ],
            'query' => array_merge(['format' => 'json'], $this->getQuery()),
            'base_uri' => self::BASE_URL,
        ];
    }

    protected function getSerializer(): Serializer
    {
        $extractor = new PropertyInfoExtractor(
            [],
            [new PhpDocExtractor()]
        );
        $normalizers = [
            new ArrayDenormalizer(),
            new ObjectNormalizer(null, null, null, $extractor),
        ];
        $encoders = [
            new JsonEncoder(),
        ];

        return new Serializer(
            $normalizers,
            $encoders
        );
    }

    abstract protected function getResponse(string $response): string;

    abstract protected function configure(OptionsResolver $resolver): void;

    abstract protected function getUrl(): string;

    abstract protected function getResponseClass(): string;
}

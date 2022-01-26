<?php

declare(strict_types=1);

namespace Lsv\Rejseplan;

use Nyholm\Psr7\Request as HttpRequest;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractRequest implements Request
{
    protected const BASE_URL = 'https://xmlopen.rejseplanen.dk/bin/rest.exe/';
    private const API_NAME = 'rejseplan_php_api';
    private const API_VERSION = '3.0';
    private const USER_AGENT = self::API_NAME.'/'.self::API_VERSION;

    /**
     * @phpstan-var  array<array-key, mixed>
     */
    protected array $options = [];

    /**
     * @phpstan-var  array<array-key, mixed>
     */
    protected array $resolvedOptions = [];
    private static ClientInterface $client;
    private ?ResponseInterface $response = null;
    private ?RequestInterface $request = null;

    public static function setClient(ClientInterface $client): void
    {
        self::$client = $client;
    }

    public function getQuery(): array
    {
        return $this->resolvedOptions;
    }

    public function getRequest(): ?RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    protected function makeRequest(): mixed
    {
        $resolver = new OptionsResolver();
        $this->configure($resolver);
        $this->resolvedOptions = $resolver->resolve($this->options);

        $this->request = new HttpRequest(
            $this->getMethod(),
            $this->makeUrl(),
            $this->getHeaders()
        );
        $this->response = self::$client->sendRequest($this->request);

        return $this->getSerializer()->deserialize(
            $this->makeResponse((string) $this->response->getBody()),
            $this->getResponseClass(),
            'json'
        );
    }

    private function getMethod(): string
    {
        return 'GET';
    }

    /**
     * @phpstan-return array<array-key, string>
     */
    private function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'User-Agent' => self::USER_AGENT,
        ];
    }

    private function getSerializer(): Serializer
    {
        $reflectionExtractor = new ReflectionExtractor();
        $phpDocExtractor = new PhpDocExtractor();

        $propertyTypeExtractor = new PropertyInfoExtractor(
            [$reflectionExtractor],
            [$phpDocExtractor, $reflectionExtractor],
            [$phpDocExtractor],
            [$reflectionExtractor],
            [$reflectionExtractor]
        );

        $normalizers = [
            new ArrayDenormalizer(),
            new DateTimeNormalizer(),
            new ObjectNormalizer(propertyTypeExtractor: $propertyTypeExtractor),
        ];

        $encoders = [
            new JsonEncoder(),
        ];

        return new Serializer($normalizers, $encoders);
    }

    abstract protected function makeResponse(string $response): string;

    abstract protected function configure(OptionsResolver $resolver): void;

    abstract protected function getUrl(): string;

    protected function makeUrl(): string
    {
        $query = array_merge(['format' => 'json'], $this->getQuery());
        $query = http_build_query($query);

        return sprintf('%s%s?%s', self::BASE_URL, $this->getUrl(), $query);
    }

    abstract protected function getResponseClass(): string;
}

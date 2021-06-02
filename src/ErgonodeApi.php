<?php

namespace Flooris\ErgonodeApi;

use GuzzleHttp\Client;
use Flooris\ErgonodeApi\Attributes\ProductClient;
use Flooris\ErgonodeApi\Attributes\TemplateClient;
use Flooris\ErgonodeApi\Attributes\AttributeClient;
use Flooris\ErgonodeApi\Attributes\MultimediaClient;
use Flooris\ErgonodeApi\Attributes\ProductListClient;

class ErgonodeApi
{
    private ClientAuthenticator $clientAuthenticator;
    private Client $httpClient;

    public function __construct(string $hostname, string $username, string $password, ?array $httpClientConfig = null)
    {
        $this->setHttpClient($hostname, $httpClientConfig);
        $this->loginHttpClient($this->httpClient, $username, $password);
    }

    public function attributes(): AttributeClient
    {
        return new AttributeClient($this);
    }

    public function multiMedia(): MultimediaClient
    {
        return new MultimediaClient($this);
    }

    public function products(): ProductClient
    {
        return new ProductClient($this);
    }

    public function productsList(): ProductListClient
    {
        return new ProductListClient($this);
    }

    public function templates(): TemplateClient
    {
        return new TemplateClient($this);
    }

    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    public function getAuthenticator(): ClientAuthenticator
    {
        return $this->clientAuthenticator;
    }

    private function setHttpClient(string $hostname, ?array $httpClientConfig = null)
    {
        if (! $httpClientConfig) {
            $httpClientConfig = [];
        }

        $httpClientConfig['base_uri'] = $hostname;

        $this->httpClient = new \GuzzleHttp\Client($httpClientConfig);
    }

    private function loginHttpClient(\GuzzleHttp\Client $client, string $username, string $password)
    {
        $this->clientAuthenticator = new ClientAuthenticator($client);
        $this->clientAuthenticator->authenticate($username, $password);
    }
}

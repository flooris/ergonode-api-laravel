<?php

namespace Flooris\ErgonodeApi;

use GuzzleHttp\Client;
use Flooris\ErgonodeApi\Attributes\ProductClient;
use Flooris\ErgonodeApi\Attributes\TemplateClient;
use Flooris\ErgonodeApi\Attributes\AttributeClient;
use Flooris\ErgonodeApi\Attributes\ProductListClient;

class ErgonodeApi
{
    private ClientAuthenticator $clientAuthenticator;
    private Client $httpClient;

    public function __construct(private string $locale, string $hostname, string $username, string $password, ?array $httpClientConfig = null)
    {
        $this->setHttpClient($hostname, $httpClientConfig);
        $this->loginHttpClient($this->httpClient, $username, $password);
    }

    public function attributes(?string $modelClass = null): AttributeClient
    {
        return new AttributeClient($this, $modelClass);
    }

    public function products(?string $modelClass = null): ProductClient
    {
        return new ProductClient($this, $modelClass);
    }

    public function templates(?string $modelClass = null, ?string $endpoint = null): TemplateClient
    {
        return new TemplateClient($this, $modelClass, $endpoint);
    }

    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    public function getAuthenticator(): ClientAuthenticator
    {
        return $this->clientAuthenticator;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    private function setHttpClient(string $hostname, ?array $httpClientConfig = null)
    {
        if (! $httpClientConfig) {
            $httpClientConfig = [];
        }

        $httpClientConfig['base_uri'] = $hostname;

        $this->httpClient = new Client($httpClientConfig);
    }

    private function loginHttpClient(Client $client, string $username, string $password)
    {
        $this->clientAuthenticator = new ClientAuthenticator($client);
        $this->clientAuthenticator->authenticate($username, $password);
    }
}

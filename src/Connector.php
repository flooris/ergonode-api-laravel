<?php

namespace Flooris\ErgonodeApi;

use stdClass;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class Connector
{
    private const HTTP_GET = 'GET';
    private const HTTP_POST = 'POST';
    private const HTTP_PUT = 'PUT';
    private const HTTP_DELETE = 'DELETE';

    private Client $httpClient;
    private Authenticator $authenticator;

    public function __construct(private string $locale, private string $hostname, string $username, string $password)
    {
        $this->httpClient = new Client([
            'base_uri' => $hostname,
        ]);

        $this->authenticator = new Authenticator($this->httpClient);
        $this->authenticator->authenticate($username, $password);
    }

    public function get(string $uri, array $query = [], array $uriParameters = []): stdClass
    {
        return $this->send(method: static::HTTP_GET, uri: $this->buildUri($uri, $uriParameters), query: $query);
    }

    public function post(string $uri, array $data, array $uriParameters = []): stdClass
    {
        return $this->send(static::HTTP_POST, $this->buildUri($uri, $uriParameters), $data);
    }

    public function put(string $uri, array $data, array $query = [], array $uriParameters = []): stdClass
    {
        return $this->send(static::HTTP_PUT, $this->buildUri($uri, $uriParameters), $data, $query);
    }

    public function delete(string $uri, array $data = [], array $uriParameters = []): stdClass
    {
        return $this->send(static::HTTP_DELETE, $this->buildUri($uri, $uriParameters), $data);
    }

    private function send(string $method, string $uri, array $data = [], array $query = []): stdClass
    {
        $options = [
            RequestOptions::HEADERS     => [
                'User-Agent'       => config('ergonode.client-options.user-agent', 'flooris/ergonode-api'),
                'Content-Type'     => 'application/json',
                'Accept'           => 'application/json',
                'JWTAuthorization' => "Bearer " . $this->authenticator->getBearerToken(),
            ],
            RequestOptions::SYNCHRONOUS => true,
            RequestOptions::DEBUG       => false,
            RequestOptions::QUERY       => $query,
        ];

        if (! empty($data)) {
            $options[RequestOptions::JSON] = $data;
        }

        return $this->decodeResponse($this->httpClient->request($method, $uri, $options));
    }

    private function decodeResponse(ResponseInterface $response): stdClass
    {
        return json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
    }

    private function buildUri(string $uri, array $uriParameters = []): string
    {

        $localeUri = ltrim("$this->locale/$uri", '/');

        return vsprintf($localeUri, $uriParameters);
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function __serialize(): array
    {
        return [
            'locale'        => $this->locale,
            'hostname'      => $this->hostname,
            'authenticator' => $this->authenticator,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->locale        = $data['locale'];
        $this->hostname      = $data['hostname'];
        $this->authenticator = $data['authenticator'];

        $this->httpClient = new Client([
            'base_uri' => $this->hostname,
        ]);

        $this->authenticator->setHttpClient($this->httpClient);
    }
}

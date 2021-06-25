<?php

namespace Flooris\ErgonodeApi;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\GuzzleException;

class Authenticator
{
    private Client $httpClient;
    private string $cacheKeyTokens = 'ERGONODE_API_TOKENS';
    private string $bearerToken;
    private string $refreshToken;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function authenticate(string $username, string $password): void
    {
        if (Cache::has($this->cacheKeyTokens)) {
            $tokens = Cache::get($this->cacheKeyTokens);

            $this->setBearerToken($tokens->token);
            $this->setRefreshToken($tokens->refresh_token);

            return;
        }

        $loginBody = [
            'username' => $username,
            'password' => $password,
        ];

        $options = [
            RequestOptions::HEADERS     => [
                'User-Agent'   => config('ergonode.client-options.user-agent', 'flooris/ergonode-api'),
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ],
            RequestOptions::JSON        => $loginBody,
            RequestOptions::SYNCHRONOUS => true,
            RequestOptions::DEBUG       => false,
        ];

        try {

            $response = $this->httpClient->post('login', $options);

        } catch (GuzzleException $exception) {
            throw $exception;
        }

        $tokens = json_decode($response->getBody());

        $this->setBearerToken($tokens->token);
        $this->setRefreshToken($tokens->refresh_token);

        $lifeTimeSeconds = (60 * 60 * 24); // 24 hours
        Cache::set($this->cacheKeyTokens, $tokens, $lifeTimeSeconds);
    }

    public function setCacheKeyTokens(string $cacheKeyTokens): void
    {
        $this->cacheKeyTokens = $cacheKeyTokens;
    }

    public function getBearerToken(): string
    {
        return $this->bearerToken;
    }

    public function setBearerToken(string $bearerToken): void
    {
        $this->bearerToken = $bearerToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }
}

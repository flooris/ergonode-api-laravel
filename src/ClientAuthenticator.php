<?php

namespace Flooris\ErgonodeApi;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\GuzzleException;

class ClientAuthenticator
{
    private \GuzzleHttp\Client $httpClient;
    private string $cacheKeyTokens = 'ERGONODE_API_TOKENS';
    private string $bearerToken;
    private string $refreshToken;

    public function __construct(\GuzzleHttp\Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function authenticate(string $username, string $password): void
    {
        if (cache()->has($this->cacheKeyTokens)) {
            $tokens = cache($this->cacheKeyTokens);

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
        cache()->set($this->cacheKeyTokens, $tokens, $lifeTimeSeconds);
    }

    public function setCacheKeyTokens(string $cacheKeyTokens): void
    {
        $this->cacheKeyTokens = $cacheKeyTokens;
    }

    /**
     * @return mixed
     */
    public function getBearerToken()
    {
        return $this->bearerToken;
    }

    /**
     * @param mixed $bearerToken
     */
    public function setBearerToken($bearerToken): void
    {
        $this->bearerToken = $bearerToken;
    }

    /**
     * @return mixed
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @param mixed $refreshToken
     */
    public function setRefreshToken($refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }
}

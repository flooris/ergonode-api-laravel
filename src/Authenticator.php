<?php

namespace Flooris\ErgonodeApi;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Cache;
use Flooris\ErgonodeApi\Api\Exceptions\ErgonodeConnectionError;

class Authenticator
{
    private string $cacheKeyTokens = 'ERGONODE_API_TOKENS';
    private string $bearerToken;
    private string $refreshToken;
    public bool $loggedIn;

    public function __construct(private Client $httpClient)
    {
        $this->loggedIn = $this->cacheTokensValid();
    }

    private function cacheTokensValid()
    {
        if (Cache::has($this->cacheKeyTokens)) {
            $tokens = Cache::get($this->cacheKeyTokens);

            $this->setBearerToken($tokens->token);
            $this->setRefreshToken($tokens->refresh_token);

            return true;
        }
        return false;
    }

    public function authenticate(string $username, string $password): void
    {
        $loggedInByCache = $this->cacheTokensValid();

        if ($loggedInByCache){
            $this->loggedIn = true;
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

        } catch (Exception $exception) {
            $this->loggedIn = false;
            $errorMsg = $exception->getMessage();
            throw new ErgonodeConnectionError("Could not login. ${errorMsg}");
        }

        $tokens = json_decode($response->getBody());

        $this->setBearerToken($tokens->token);
        $this->setRefreshToken($tokens->refresh_token);

        $lifeTimeSeconds = (60 * 60 * 24); // 24 hours
        Cache::set($this->cacheKeyTokens, $tokens, $lifeTimeSeconds);
        $this->loggedIn = true;
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

    public function setHttpClient(Client $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    public function __serialize(): array
    {
        return [
            'cacheKeyTokens' => $this->cacheKeyTokens,
            'bearerToken'    => $this->bearerToken,
            'refreshToken'   => $this->refreshToken,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->cacheKeyTokens = $data['cacheKeyTokens'];
        $this->bearerToken    = $data['bearerToken'];
        $this->refreshToken   = $data['refreshToken'];
    }
}

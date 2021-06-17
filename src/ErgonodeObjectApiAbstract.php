<?php

namespace Flooris\ErgonodeApi;

use Exception;
use JsonException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;
use Flooris\ErgonodeApi\Attributes\ErgonodeModel;
use Flooris\ErgonodeApi\Attributes\ErgonodeClient;

abstract class ErgonodeObjectApiAbstract implements ErgonodeClient
{
    public ?ErgonodeModel $model;

    public function __construct(private ErgonodeApi $ergonodeApi, private string $endpointSlug, protected string $modelClass)
    {
    }

    public function getErgonodeApi(): ErgonodeApi
    {
        return $this->ergonodeApi;
    }

    public function getLocale(): string
    {
        return $this->ergonodeApi->getLocale();
    }

    /**
     * @throws GuzzleException|JsonException
     */
    public function find(string $id, ?string $extra = null): ?ErgonodeModel
    {
        if ($extra) {
            $this->model = $this->getModel("{$this->getLocale()}/$this->endpointSlug/$id/$extra", $this->modelClass);
        } else {
            $this->model = $this->getModel("{$this->getLocale()}/$this->endpointSlug/$id", $this->modelClass);
        }

        return $this->model;
    }

    /**
     * @throws GuzzleException|JsonException
     */
    public function firstWhere(string $key, mixed $value): ?ErgonodeModel
    {
        $itemCollection = $this->filter("$key=$value");

        $this->model = $itemCollection->where($key, $value)->first();

        return $this->model;
    }

    /**
     * @param ErgonodeModel|null $parentModel
     * @return Collection
     * @throws GuzzleException
     * @throws JsonException
     */
    public function all(?ErgonodeModel $parentModel = null): Collection
    {
        return $this->getCollection("{$this->getLocale()}/$this->endpointSlug", $this->modelClass, null, $parentModel);
    }

    /**
     * @throws GuzzleException|JsonException
     */
    public function filter(string $searchQuery): Collection
    {
        $options = [
            RequestOptions::QUERY => [
                'filter' => $searchQuery,
            ],
        ];

        return $this->getCollectionByIndex("{$this->getLocale()}/$this->endpointSlug", $this->modelClass, $options);
    }

    /**
     * @throws GuzzleException
     */
    public function create(array $body): bool
    {
        $options = [
            RequestOptions::JSON => $body,
        ];

        $options = $this->getHttpRequestOptions($options);

        $this->getErgonodeApi()->getHttpClient()->post("{$this->getLocale()}/$this->endpointSlug", $options);

        return true;
    }

    /**
     * @throws GuzzleException
     */
    public function update(string $id, array $body): bool
    {
        $options = [
            RequestOptions::JSON => $body,
        ];

        $options = $this->getHttpRequestOptions($options);

        $this->getErgonodeApi()->getHttpClient()->put("{$this->getLocale()}/$this->endpointSlug/$id", $options);

        return true;
    }

    /**
     * @throws GuzzleException
     */
    public function append(string $entityUri, array $body): bool
    {
        $options = [
            RequestOptions::JSON => $body,
        ];

        $options = $this->getHttpRequestOptions($options);

        $this->getErgonodeApi()
            ->getHttpClient()
            ->patch("{$this->getLocale()}/$this->endpointSlug/$entityUri", $options);

        return true;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function upload(string $entityUri, array $imageData)
    {
        $options = [
            RequestOptions::MULTIPART => [
                [
                    'name'     => 'upload',
                    'contents' => $imageData['image'],
                    'filename' => $imageData['file_name'],
                ],
            ],

        ];

        $options = $this->getHttpRequestOptions($options);

        try {
            return json_decode($this->getErgonodeApi()
                ->getHttpClient()
                ->post($entityUri, $options)
                ->getBody()
                ->getContents(), false, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @throws GuzzleException
     */
    protected function get(string $uri, ?array $options = null): ResponseInterface
    {
        $options = $this->getHttpRequestOptions($options);

        return $this->getErgonodeApi()
            ->getHttpClient()
            ->get($uri, $options);
    }

    /**
     * @throws GuzzleException|JsonException
     */
    private function getModel(string $uri, string $modelClass, ?array $options = null): ?ErgonodeModel
    {
        try {
            $responseObject = json_decode($this->get($uri, $options)
                ->getBody()
                ->getContents(), false, 512, JSON_THROW_ON_ERROR);

            return new $modelClass($this, $responseObject);

        } catch (GuzzleException $exception) {
            if ($exception->getCode() === 404) {
                return null;
            }

            throw $exception;
        }

    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    private function getCollectionByIndex(string $uri, string $modelClass, ?array $options = null): Collection
    {
        $collection   = collect();
        $responseData = json_decode($this->get($uri, $options)
            ->getBody()
            ->getContents(), false, 512, JSON_THROW_ON_ERROR);

        $items = [];

        if (is_array($responseData)) {
            $items = $responseData;
        } else if (isset($responseData->collection)) {
            $items = $responseData->collection;
        }

        foreach ($items as $responseObject) {
            $responseObjectDetail = json_decode($this->get("$uri/$responseObject->id")
                ->getBody()
                ->getContents(), false, 512, JSON_THROW_ON_ERROR);

            $collection = $collection->push((new $modelClass($this, $responseObjectDetail)));
        }

        return $collection;
    }

    /**
     * @param string        $uri
     * @param string        $modelClass
     * @param array|null    $options
     * @param ErgonodeModel $parentModel
     * @return Collection
     * @throws GuzzleException
     * @throws JsonException
     */
    private function getCollection(string $uri, string $modelClass, ?array $options = null, ?ErgonodeModel $parentModel = null): Collection
    {
        $collection   = collect();
        $responseData = json_decode($this->get($uri, $options)
            ->getBody()
            ->getContents(), false, 512, JSON_THROW_ON_ERROR);

        $items = [];

        if (is_array($responseData)) {
            $items = $responseData;
        } else if (isset($responseData->collection)) {
            $items = $responseData->collection;
        }

        foreach ($items as $responseObject) {
            $collection = $parentModel ? $collection->push((new $modelClass($this, $responseObject, $parentModel)))
                : $collection->push((new $modelClass($this, $responseObject)));
        }

        return $collection;
    }

    private function getHttpRequestOptions(?array $options = null): array
    {
        $defaultOptions = [
            RequestOptions::HEADERS     => [
                'User-Agent'       => config('ergonode.client-options.user-agent', 'flooris/ergonode-api'),
                'Accept'           => 'application/json',
                'Content-Type'     => 'application/json',
                'JWTAuthorization' => "Bearer " . $this->getErgonodeApi()->getAuthenticator()->getBearerToken(),
            ],
            RequestOptions::SYNCHRONOUS => true,
            RequestOptions::DEBUG       => false,
        ];

        if (! $options) {
            return $defaultOptions;
        }

        if (isset($options[RequestOptions::HEADERS])) {
            $options[RequestOptions::HEADERS] = array_merge($options[RequestOptions::HEADERS], $defaultOptions[RequestOptions::HEADERS]);
        } else {
            $options[RequestOptions::HEADERS] = $defaultOptions[RequestOptions::HEADERS];
        }

        if (! isset($options[RequestOptions::SYNCHRONOUS])) {
            $options[RequestOptions::SYNCHRONOUS] = true;
        }

        if (! isset($options[RequestOptions::DEBUG])) {
            $options[RequestOptions::DEBUG] = false;
        }

        if (isset($options[RequestOptions::MULTIPART], $options[RequestOptions::HEADERS]['Content-Type'])) {
            unset($options[RequestOptions::HEADERS]['Content-Type']);
        }

        return $options;
    }

    public function model(): ?ErgonodeModel
    {
        return $this->model;
    }
}

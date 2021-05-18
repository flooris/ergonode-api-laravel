<?php

namespace Flooris\ErgonodeApi;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;

abstract class ErgonodeObjectApiAbstract
{
    private ErgonodeApi $ergonodeApi;
    private string $endpointSlug;
    private string $modelClass;
    public $model;

    public function __construct(ErgonodeApi $connector, string $endpointSlug, string $modelClass)
    {
        $this->ergonodeApi  = $connector;
        $this->endpointSlug = $endpointSlug;
        $this->modelClass   = $modelClass;
    }

    public function getErgonodeApi(): ErgonodeApi
    {
        return $this->ergonodeApi;
    }

    /**
     * @throws GuzzleException
     */
    public function find(string $locale, string $id): bool
    {
        $this->model = $this->getModel("{$locale}/{$this->endpointSlug}/{$id}", $this->modelClass, $locale);

        return (bool)$this->model;
    }

    /**
     * @param string $locale
     * @param int    $limit
     * @param array  $columns
     * @throws GuzzleException
     */
    public function all(string $locale, array $columns,int $limit = 25, $page = 1,): \stdClass
    {
        $offset = $limit * $page;
        $combinedColumns = implode(',', $columns);
        $products= json_decode($this->get("{$locale}/products?offset={$offset}&limit={$limit}&extended=true&columns={$combinedColumns}")
            ->getBody()
            ->getContents());
        
        $totalPages = ceil($products->info->count / $limit) - 1;

        if ( $page > $totalPages ){
            throw new \Exception("You have exceeded the offset of the pages. there are {$totalPages}, you have given {$page}", 400);
        }

        return $products;
    }

    /**
     * @throws GuzzleException
     */
    public function filter(string $locale, string $searchQuery): Collection
    {
        $options = [
            RequestOptions::QUERY => [
                'filter' => $searchQuery,
            ],
        ];

        return $this->getCollectionByIndex("{$locale}/{$this->endpointSlug}", $this->modelClass, $locale, $options);
    }

    public function create(string $locale, array $body): bool
    {
        $options = [
            RequestOptions::JSON => $body,
        ];

        $options = $this->getHttpRequestOptions($options);

        $this->getErgonodeApi()->getHttpClient()->post("{$locale}/{$this->endpointSlug}", $options);

        return true;
    }

    public function update(string $locale, string $id, array $body): bool
    {
        $options = [
            RequestOptions::JSON => $body,
        ];

        $options = $this->getHttpRequestOptions($options);

        $this->getErgonodeApi()->getHttpClient()->put("{$locale}/{$this->endpointSlug}/{$id}", $options);

        return true;
    }

    public function append(string $locale, string $entityUri, array $body): bool
    {
        $options = [
            RequestOptions::JSON => $body,
        ];

        $options = $this->getHttpRequestOptions($options);

        $this->getErgonodeApi()->getHttpClient()->patch("{$locale}/{$this->endpointSlug}/{$entityUri}", $options);

        return true;
    }

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
            return $response = json_decode($this->getErgonodeApi()->getHttpClient()->post("{$entityUri}", $options)->getBody()->getContents());
        }catch (\Exception $exception){
            throw $exception;
        }
    }

    /**
     * @throws GuzzleException
     */
    private function get(string $uri, ?array $options = null): ResponseInterface
    {
        $options = $this->getHttpRequestOptions($options);

        return $this->getErgonodeApi()
            ->getHttpClient()
            ->get($uri, $options);
    }

    /**
     * @throws GuzzleException
     */
    private function getModel(string $uri, string $modelClass, string $locale, ?array $options = null)
    {
        try {
            $responseObject = json_decode(
                $this->get($uri, $options)
                    ->getBody()
                    ->getContents()
            );

            return new $modelClass(
                client: $this,
                responseObject: $responseObject,
                locale: $locale
            );

        } catch (GuzzleException $exception) {
            if ($exception->getCode() === 404) {
                return null;
            }

            throw $exception;
        }

    }

    /**
     * @throws GuzzleException
     */
    private function getCollectionByIndex(string $uri, string $modelClass, string $locale, ?array $options = null): Collection
    {
        $collection   = collect();
        $responseData = json_decode(
            $this->get($uri, $options)
                ->getBody()
                ->getContents()
        );

        $items = [];

        if (is_array($responseData)) {
            $items = $responseData;
        } else if (isset($responseData->collection)) {
            $items = $responseData->collection;
        }

        foreach ($items as $responseObject) {
            $responseObjectDetail = json_decode(
                $this->get("{$uri}/{$responseObject->id}")
                    ->getBody()
                    ->getContents()
            );

            $collection = $collection->push((new $modelClass(
                client: $this,
                responseObject: $responseObjectDetail,
                locale: $locale
            )));
        }

        return $collection;
    }

    /**
     * @throws GuzzleException
     */
    private function getCollection(string $uri, string $modelClass, string $locale, ?array $options = null): Collection
    {
        $collection   = collect();
        $responseData = json_decode(
            $this->get($uri, $options)
                ->getBody()
                ->getContents()
        );

        $items = [];

        if (is_array($responseData)) {
            $items = $responseData;
        } else if (isset($responseData->collection)) {
            $items = $responseData->collection;
        }

        foreach ($items as $responseObject) {
            $collection = $collection->push((new $modelClass(
                client: $this,
                responseObject: $responseObject,
                locale: $locale
            )));
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

        if (isset($options[RequestOptions::MULTIPART]) && isset($options[RequestOptions::HEADERS]['Content-Type'])){
            unset($options[RequestOptions::HEADERS]['Content-Type']);
        }

        return $options;
    }

    public function getAttributeOption(string $locale, $attributeCode, $optionCode)
    {
        return json_decode($this->get("$locale/attributes/{$attributeCode}/options/{$optionCode}")->getBody()->getContents());
    }

}
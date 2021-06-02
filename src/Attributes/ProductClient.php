<?php

namespace Flooris\ErgonodeApi\Attributes;

use JsonException;
use Flooris\ErgonodeApi\ErgonodeApi;
use GuzzleHttp\Exception\GuzzleException;
use Flooris\ErgonodeApi\ErgonodeObjectApiAbstract;

class ProductClient extends ErgonodeObjectApiAbstract
{
    public const ENDPOINT = 'products';

    public function __construct(ErgonodeApi $connector, ?string $modelClass = null)
    {
        parent::__construct(
            $connector,
            static::ENDPOINT,
            $modelClass ?? ProductModel::class
        );
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function findBySku(string $sku): ?ProductModel
    {
        $itemCollection = $this->filter("sku={$sku}");

        $this->model = $itemCollection->where('sku', $sku)->first();

        return $this->model;
    }
}

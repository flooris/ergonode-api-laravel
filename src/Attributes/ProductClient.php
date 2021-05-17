<?php

namespace Flooris\ErgonodeApi\Attributes;

use Flooris\ErgonodeApi\ErgonodeApi;
use Flooris\ErgonodeApi\ErgonodeObjectApiAbstract;

class ProductClient extends ErgonodeObjectApiAbstract
{
    const ENDPOINT = 'products';

    public function __construct(ErgonodeApi $connector)
    {
        return parent::__construct(
            $connector,
            ProductClient::ENDPOINT,
            ProductModel::class
        );
    }

    public function findBySku(string $locale, string $sku)
    {
        $itemCollection = $this->filter($locale, "sku={$sku}");

        $this->model = $itemCollection->where('sku', $sku)->first();

        return Bool($this->model);
    }

    public function getProductBySku(string $locale, string $sku)
    {
        $itemCollection = $this->filter($locale, "sku={$sku}");

        $this->model = $itemCollection->where('sku', $sku)->first();

        return $this->model;
    }

    public function model(): ?ProductModel
    {
        return $this->model;
    }
}
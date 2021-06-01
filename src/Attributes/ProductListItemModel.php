<?php

namespace Flooris\ErgonodeApi\Attributes;

use Illuminate\Support\Collection;

class ProductListItemModel
{
    private ProductListClient $client;
    public string $locale;
    public \stdClass $responseObject;
    public string $id;
    public string $sku;
    public array $pimAttributes;
    private Collection $pimAttributeOptions;

    public function __construct(ProductListClient $client, \stdClass $responseObject, string $locale)
    {
        $this->client              = $client;
        $this->responseObject      = $responseObject;
        $this->locale              = $locale;
        $this->id                  = $responseObject->id->value;
        $this->sku                 = $responseObject->sku->value;
        $this->pimAttributes       = json_decode(json_encode($responseObject), true);
        $this->pimAttributeOptions = collect();
    }

    public function toArray()
    {
        return [];
    }
}

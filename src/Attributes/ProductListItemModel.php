<?php

namespace Flooris\ErgonodeApi\Attributes;

class ProductListItemModel
{
    private ProductListClient $client;
    public string $locale;
    public \stdClass $responseObject;
    public string $id;
    public string $sku;
    public int $index;

    public function __construct(ProductListClient $client, \stdClass $responseObject, string $locale)
    {
        $this->client         = $client;
        $this->locale         = $locale;
        $this->responseObject = $responseObject;
        $this->id             = $responseObject->id;
        $this->sku            = $responseObject->sku;
        $this->index          = (int)$responseObject->index;
    }
}
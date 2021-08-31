<?php

namespace Flooris\ErgonodeApi;

use Flooris\ErgonodeApi\Api\ProductClient;
use Flooris\ErgonodeApi\Api\AttributeClient;
use Flooris\ErgonodeApi\Api\MultimediaClient;
use Flooris\ErgonodeApi\Api\AttributeOptionClient;

class ErgonodeApi
{
    public Connector $connector;

    public function __construct(string $locale, string $hostname, string $username, string $password)
    {
        $this->connector = new Connector($locale, $hostname, $username, $password);
    }

    public function products(?string $modelClass = null): ProductClient
    {
        return new ProductClient($this, $modelClass);
    }

    public function attributes(?string $modelClass = null): AttributeClient
    {
        return new AttributeClient($this, $modelClass);
    }

    public function attributeOptions(string $parentId, ?string $modelClass = null): AttributeOptionClient
    {
        return new AttributeOptionClient(parentId: $parentId, ergonodeApi: $this, modelClass: $modelClass);
    }

    public function multimedia(?string $modelClass = null): MultimediaClient
    {
        return new MultimediaClient($this, $modelClass);
    }
}

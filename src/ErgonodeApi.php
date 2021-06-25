<?php

namespace Flooris\ErgonodeApi;

use Flooris\ErgonodeApi\Api\ProductClient;
use Flooris\ErgonodeApi\Api\AttributeClient;

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
}

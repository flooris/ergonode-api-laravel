<?php

namespace Flooris\ErgonodeApi\Models;

use Flooris\ErgonodeApi\Api\ProductClient;
use Flooris\ErgonodeApi\Models\Contracts\Listable;
use Illuminate\Contracts\Routing\UrlRoutable;
use Flooris\ErgonodeApi\Models\Traits\ListableTrait;
use Flooris\ErgonodeApi\Models\Traits\UrlRoutableTrait;

class ProductBaseModel extends AbstractModel implements UrlRoutable, Listable
{
    use UrlRoutableTrait, ListableTrait;

    public int $index;
    public string $sku;
    public array $_links = [];

    public function clientClass(): string
    {
        return ProductClient::class;
    }

    public function template(?string $productId = null): TemplateModel
    {
        return $this->client->template($productId ?? $this->id);
    }
}

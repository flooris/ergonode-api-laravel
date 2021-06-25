<?php

namespace Flooris\Ergonode\Models;

use Flooris\Ergonode\Api\ProductClient;
use Flooris\Ergonode\Models\Contracts\Listable;
use Illuminate\Contracts\Routing\UrlRoutable;
use Flooris\Ergonode\Models\Traits\ListableTrait;
use Flooris\Ergonode\Models\Traits\UrlRoutableTrait;

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

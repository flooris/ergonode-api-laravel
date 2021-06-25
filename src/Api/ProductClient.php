<?php

namespace Flooris\ErgonodeApi\Api;

use Flooris\ErgonodeApi\Models\ModelFactory;
use Flooris\ErgonodeApi\Models\ProductListModel;
use Flooris\ErgonodeApi\Models\ProductModel;
use Flooris\ErgonodeApi\Api\Traits\ListableTrait;
use Flooris\ErgonodeApi\Api\Contracts\Listable;
use Flooris\ErgonodeApi\Models\TemplateModel;

class ProductClient extends AbstractClient implements Listable
{
    use ListableTrait;

    public function modelClass(): string
    {
        return ProductModel::class;
    }

    public function listModelClass(): string
    {
        return ProductListModel::class;
    }

    public function listUri(): string
    {
        return 'products';
    }

    public function baseUri(): string
    {
        return 'products';
    }

    public function singleUri(): string
    {
        return 'products/%s';
    }

    public function template(string $productId): TemplateModel
    {
        return ModelFactory::create($this, $this->getModelRaw('products/%s/template', uriParameters: [$productId]), TemplateModel::class);
    }
}

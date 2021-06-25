<?php

namespace Flooris\Ergonode\Api;

use Flooris\Ergonode\Models\ModelFactory;
use Flooris\Ergonode\Models\ProductListModel;
use Flooris\Ergonode\Models\ProductModel;
use Flooris\Ergonode\Api\Traits\ListableTrait;
use Flooris\Ergonode\Api\Contracts\Listable;
use Flooris\Ergonode\Models\TemplateModel;

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

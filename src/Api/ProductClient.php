<?php

namespace Flooris\ErgonodeApi\Api;

use Exception;
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

    public function attributesUri(): string
    {
        return 'products/attributes';
    }

    public function template(string $productId): TemplateModel
    {
        return ModelFactory::create($this, $this->getModelRaw('products/%s/template', uriParameters: [$productId]), TemplateModel::class);
    }

    public function create(string $sku, string $type, string $templateId, array $categoryIds): ProductModel
    {
        $connector = $this->ergonodeApi->connector;

        $response = $connector->post($this->baseUri(), [
            'sku'         => $sku,
            'type'        => $type,
            'templateId'  => $templateId,
            'categoryIds' => $categoryIds,
        ]);

        return $this->find($response->id);
    }

    public function update(string $productId, array $attributes): ProductModel
    {
        $connector = $this->ergonodeApi->connector;

        try {
            $connector->patch($this->attributesUri(), [
                'data' => [
                    (object)[
                        'id'      => $productId,
                        'payload' => $attributes,
                    ],
                ],
            ]);
        } catch (Exception $e) {
            //Catches the error that can not resolve the empty response, anything else will error out.
            if ($e->getCode() !== 4) {
                throw $e;
            }
        }

        return $this->find($productId);
    }
}
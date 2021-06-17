<?php

namespace Flooris\ErgonodeApi\Attributes;

use JsonException;
use Flooris\ErgonodeApi\ErgonodeApi;
use GuzzleHttp\Exception\GuzzleException;
use Flooris\ErgonodeApi\ErgonodeObjectApiAbstract;

class AttributeClient extends ErgonodeObjectApiAbstract
{
    public const ENDPOINT = 'attributes';

    public function __construct(ErgonodeApi $connector, ?string $modelClass = null)
    {
        parent::__construct(
            $connector,
            static::ENDPOINT,
            $modelClass ?? AttributeOptionModel::class
        );
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function findByCode(string $code): ?AttributeModel
    {
        $this->model = $this->all()->where('code', $code)->first();

        return $this->model;
    }

    public function fillModel(string $id, string $code, ?string $label = null, array $options = []): AttributeModel
    {
        $attributeModel       = new AttributeModel();
        $attributeModel->id   = $id;
        $attributeModel->code = $code;
        if ($label) {
            $attributeModel->label = $label;
        }
        if (count($options)) {
            $attributeModel->setOptions($options);
        }
        $this->model = $attributeModel;

        return $this->model;
    }

}

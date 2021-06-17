<?php

namespace Flooris\ErgonodeApi\Attributes;

use JsonException;
use Flooris\ErgonodeApi\ErgonodeApi;
use GuzzleHttp\Exception\GuzzleException;
use Flooris\ErgonodeApi\ErgonodeObjectApiAbstract;

class AttributeOptionClient extends ErgonodeObjectApiAbstract
{
    public function __construct(
        ErgonodeApi $connector,
        public AttributeModel $attribute,
        ?string $modelClass = null
    )
    {
        $endpoint = "attributes/{$attribute->id}/options";

        parent::__construct(
            $connector,
            $endpoint,
            $modelClass ?? AttributeOptionModel::class
        );
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function findByCode(string $code): ?AttributeOptionModel
    {
        $this->model = $this->all()->where('code', $code)->first();

        return $this->model;
    }

    public function findById($id)
    {
        $this->model = $this->all($this->attribute)->where('id', $id)->first();

        return $this->model;
    }
}

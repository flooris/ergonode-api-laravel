<?php

namespace Flooris\ErgonodeApi\Attributes;

use Flooris\ErgonodeApi\ErgonodeApi;
use Flooris\ErgonodeApi\ErgonodeObjectApiAbstract;

class AttributeOptionClient extends ErgonodeObjectApiAbstract
{
    public function __construct(ErgonodeApi $connector, AttributeModel $attribute)
    {
        $endpoint = "attributes/{$attribute->id}/options";

        return parent::__construct(
            $connector,
            $endpoint,
            AttributeOptionModel::class
        );
    }

    public function findByCode(string $locale, string $code): bool
    {
        $this->model = $this->all($locale)->where('code', $code)->first();

        return (bool)$this->model;
    }

    public function model(): ?AttributeOptionModel
    {
        return $this->model;
    }
}
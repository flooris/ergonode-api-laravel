<?php

namespace Flooris\ErgonodeApi\Attributes;

use Flooris\ErgonodeApi\ErgonodeApi;
use Flooris\ErgonodeApi\ErgonodeObjectApiAbstract;

class AttributeClient extends ErgonodeObjectApiAbstract
{
    const ENDPOINT = 'attributes';

    public function __construct(ErgonodeApi $connector)
    {
        return parent::__construct(
            $connector,
            AttributeClient::ENDPOINT,
            AttributeModel::class
        );
    }

    public function findByCode(string $locale, string $code): bool
    {
        $this->model = $this->all($locale)->where('code', $code)->first();

        return (bool)$this->model;
    }

    public function model(): ?AttributeModel
    {
        return $this->model;
    }

}
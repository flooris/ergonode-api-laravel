<?php

namespace Flooris\ErgonodeApi\Api;

use Flooris\ErgonodeApi\Api\Contracts\Listable;
use Flooris\ErgonodeApi\Api\Traits\ListableTrait;
use Flooris\ErgonodeApi\Models\AttributeListModel;
use Flooris\ErgonodeApi\Models\AttributeModel;

class AttributeClient extends AbstractClient implements Listable
{
    use ListableTrait;

    public function modelClass(): string
    {
        return AttributeModel::class;
    }

    public function listModelClass(): string
    {
        return AttributeListModel::class;
    }

    public function baseUri(): string
    {
        return 'attributes';
    }

    public function singleUri(): string
    {
       return 'attributes/%s';
    }

    public function listUri(): string
    {
        return $this->baseUri();
    }
}

<?php

namespace Flooris\Ergonode\Api;

use Flooris\Ergonode\Api\Contracts\Listable;
use Flooris\Ergonode\Api\Traits\ListableTrait;
use Flooris\Ergonode\Models\AttributeListModel;
use Flooris\Ergonode\Models\AttributeModel;

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

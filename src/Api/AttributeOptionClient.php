<?php

namespace Flooris\Ergonode\Api;

use Flooris\Ergonode\Api\Contracts\Listable;
use Flooris\Ergonode\Api\Traits\ListableTrait;
use Flooris\Ergonode\Models\AttributeOptionModel;
use Flooris\Ergonode\Models\AttributeOptionListModel;

class AttributeOptionClient extends AbstractChildClient implements Listable
{
    use ListableTrait;

    public function modelClass(): string
    {
       return AttributeOptionModel::class;
    }

    public function baseUri(): string
    {
        return 'attributes/%s/options';
    }

    public function singleUri(): string
    {
        return 'attributes/%s/options/%s';
    }

    public function listUri(): string
    {
        return 'attributes/%s/options/grid';
    }

    public function listModelClass(): string
    {
        return AttributeOptionListModel::class;
    }
}

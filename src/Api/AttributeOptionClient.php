<?php

namespace Flooris\ErgonodeApi\Api;

use Flooris\ErgonodeApi\Api\Contracts\Listable;
use Flooris\ErgonodeApi\Api\Traits\ListableTrait;
use Flooris\ErgonodeApi\Models\AttributeOptionModel;
use Flooris\ErgonodeApi\Models\AttributeOptionListModel;

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

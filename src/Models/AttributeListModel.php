<?php

namespace Flooris\ErgonodeApi\Models;

use Flooris\ErgonodeApi\Models\Contracts\ListModel;
use Flooris\ErgonodeApi\Models\Traits\ListModelTrait;

class AttributeListModel extends AttributeBaseModel implements ListModel
{
    use ListModelTrait;

    public array $templates = [];
    public array $_links = [];

    public function modelClass(): string
    {
        return AttributeModel::class;
    }
}

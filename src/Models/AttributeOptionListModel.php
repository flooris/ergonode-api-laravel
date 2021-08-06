<?php

namespace Flooris\ErgonodeApi\Models;

use Flooris\ErgonodeApi\Models\Contracts\ListModel;
use Flooris\ErgonodeApi\Models\Traits\ListModelTrait;

class AttributeOptionListModel extends AttributeOptionBaseModel implements ListModel
{
    use ListModelTrait;

    public function modelClass(): string
    {
        return AttributeOptionModel::class;
    }
}

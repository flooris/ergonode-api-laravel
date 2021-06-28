<?php

namespace Flooris\ErgonodeApi\Models;

class AttributeOptionListModel extends AttributeOptionBaseModel
{
    public function modelClass(): string
    {
        return AttributeOptionModel::class;
    }
}

<?php

namespace Flooris\Ergonode\Models;

use Flooris\Ergonode\Models\Contracts\ListModel;
use Flooris\Ergonode\Models\Traits\ListModelTrait;

class AttributeListModel extends AttributeBaseModel implements ListModel
{
    use ListModelTrait;

    public array $templates = [];
    public array $_links = [];
}

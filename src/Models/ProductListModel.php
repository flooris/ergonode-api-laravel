<?php

namespace Flooris\Ergonode\Models;

use Flooris\Ergonode\Models\Contracts\ListModel;
use Flooris\Ergonode\Models\Traits\ListModelTrait;

class ProductListModel extends ProductBaseModel implements ListModel
{
    use ListModelTrait;
}

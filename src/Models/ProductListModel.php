<?php

namespace Flooris\ErgonodeApi\Models;

use Flooris\ErgonodeApi\Models\Contracts\ListModel;
use Flooris\ErgonodeApi\Models\Traits\ListModelTrait;

class ProductListModel extends ProductBaseModel implements ListModel
{
    use ListModelTrait;
}

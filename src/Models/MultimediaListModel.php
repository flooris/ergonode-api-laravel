<?php

namespace Flooris\ErgonodeApi\Models;

use Flooris\ErgonodeApi\Models\Contracts\ListModel;
use Flooris\ErgonodeApi\Models\Traits\ListModelTrait;

class MultimediaListModel extends MultimediaModel implements ListModel
{
    use ListModelTrait;

    public function modelClass(): string
    {
        return MultimediaModel::class;
    }
}

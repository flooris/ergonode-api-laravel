<?php

namespace Flooris\Ergonode\Models\Traits;

use Exception;
use Flooris\Ergonode\Models\Contracts\Model;

trait ListModelTrait
{
    public function getFullModel(): Model
    {
        if (!$this->id) {
            throw new Exception("Can't find full model when list model is empty");
        }

        return $this->find($this->id);
    }
}

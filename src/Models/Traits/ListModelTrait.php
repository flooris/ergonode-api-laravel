<?php

namespace Flooris\ErgonodeApi\Models\Traits;

use Exception;
use Flooris\ErgonodeApi\Models\Contracts\Model;

trait ListModelTrait
{
    public function getFullModel()
    {
        if (!$this->id) {
            throw new Exception("Can't find full model when list model is empty");
        }

        return $this->find($this->id);
    }
}

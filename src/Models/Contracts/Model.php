<?php

namespace Flooris\Ergonode\Models\Contracts;

use Flooris\Ergonode\Api\Contracts\Client;

interface Model extends BaseModel
{
    public function __construct(?Client $client = null);
}

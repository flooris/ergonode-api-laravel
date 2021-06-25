<?php

namespace Flooris\ErgonodeApi\Models\Contracts;

use Flooris\ErgonodeApi\Api\Contracts\Client;

interface Model extends BaseModel
{
    public function __construct(?Client $client = null);
}

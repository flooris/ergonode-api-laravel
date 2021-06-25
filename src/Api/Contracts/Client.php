<?php

namespace Flooris\ErgonodeApi\Api\Contracts;

use Flooris\ErgonodeApi\ErgonodeApi;

interface Client extends BaseClient
{
    public function __construct(?ErgonodeApi $ergonodeApi = null, ?string $modelClass = null, ?string $listModelClass = null);
}

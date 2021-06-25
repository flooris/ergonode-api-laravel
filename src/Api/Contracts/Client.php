<?php

namespace Flooris\Ergonode\Api\Contracts;

use Flooris\Ergonode\ErgonodeApi;

interface Client extends BaseClient
{
    public function __construct(?ErgonodeApi $ergonodeApi = null, ?string $modelClass = null, ?string $listModelClass = null);
}

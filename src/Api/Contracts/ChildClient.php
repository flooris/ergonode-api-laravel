<?php

namespace Flooris\Ergonode\Api\Contracts;

use Flooris\Ergonode\ErgonodeApi;

interface ChildClient extends BaseClient
{
    public function __construct(string $parentId, ?ErgonodeApi $ergonodeApi = null, ?string $modelClass = null, ?string $listModelClass = null);
}

<?php

namespace Flooris\ErgonodeApi\Api\Contracts;

use Flooris\ErgonodeApi\ErgonodeApi;

interface ChildClient extends BaseClient
{
    public function __construct(string $parentId, ?ErgonodeApi $ergonodeApi = null, ?string $modelClass = null, ?string $listModelClass = null);
}

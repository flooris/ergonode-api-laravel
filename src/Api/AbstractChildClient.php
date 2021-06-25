<?php

namespace Flooris\Ergonode\Api;

use Flooris\Ergonode\ErgonodeApi;
use Flooris\Ergonode\Api\Contracts\ChildClient;

abstract class AbstractChildClient extends AbstractBaseClient implements ChildClient
{
    public function __construct(protected string $parentId, ?ErgonodeApi $ergonodeApi = null, ?string $modelClass = null, ?string $listModelClass = null)
    {
        parent::__construct($ergonodeApi, $modelClass, $listModelClass);
    }
}

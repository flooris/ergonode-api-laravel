<?php

namespace Flooris\ErgonodeApi\Api;

use Flooris\ErgonodeApi\ErgonodeApi;
use Flooris\ErgonodeApi\Api\Contracts\ChildClient;

abstract class AbstractChildClient extends AbstractBaseClient implements ChildClient
{
    public function __construct(protected string $parentId, ?ErgonodeApi $ergonodeApi = null, ?string $modelClass = null, ?string $listModelClass = null)
    {
        parent::__construct($ergonodeApi, $modelClass, $listModelClass);
    }
}

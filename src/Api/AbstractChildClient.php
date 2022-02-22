<?php

namespace Flooris\ErgonodeApi\Api;

use stdClass;
use Flooris\ErgonodeApi\ErgonodeApi;
use Flooris\ErgonodeApi\Api\Contracts\ChildClient;

abstract class AbstractChildClient extends AbstractBaseClient implements ChildClient
{
    public function __construct(protected string $parentId, ?ErgonodeApi $ergonodeApi = null, ?string $modelClass = null, ?string $listModelClass = null)
    {
        parent::__construct($ergonodeApi, $modelClass, $listModelClass);
    }

    public function findRaw(string $id): stdClass
    {
        return $this->getModelRaw($this->singleUri(), uriParameters: [$this->parentId, $id]);
    }

    public function getModelsRawArray(string $uri, array $columns = [], string $view = 'grid', ?int $limit = null, ?int $offset = null, array $filters = [], ?string $sortField = null, string $sortOrder = 'DESC'): array
    {
        $uriParameters = [$this->parentId];

        return json_decode(json_encode($this->getModelsRaw($uri, $columns, $view, $limit, $offset, $filters, $sortField, $sortOrder, $uriParameters)), true);
    }
}

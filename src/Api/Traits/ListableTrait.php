<?php

namespace Flooris\ErgonodeApi\Api\Traits;

use stdClass;
use Flooris\ErgonodeApi\Models\Contracts\Model;
use Flooris\ErgonodeApi\Models\ModelFactory;
use Flooris\ErgonodeApi\Api\Contracts\ChildClient;
use Illuminate\Pagination\LengthAwarePaginator;

trait ListableTrait
{
    public function list(array $columns = [], string $view = 'grid', ?int $perPage = null, int $currentPage = 1, array $filters = [], ?string $sortField = null, string $sortOrder = 'DESC'): LengthAwarePaginator
    {
        return $this->getModelsPaginated($this->listUri(), $columns, $view, $perPage, $currentPage, $filters, $sortField, $sortOrder, $this instanceof ChildClient ? [$this->parentId] : []);
    }

    public function listWithColumns(array $columns = [], ?int $perPage = null, int $currentPage = 1, array $filters = [], ?string $sortField = null, string $sortOrder = 'DESC'): stdClass
    {
        $offset         = $perPage ? ($currentPage - 1) * $perPage : null;
        $responseObject = $this->getModelsRaw($this->listUri(), $columns, 'grid', $perPage, $offset, $filters, $sortField, $sortOrder, $this instanceof ChildClient ? [$this->parentId] : []);

        $object             = new stdClass();
        $object->columns    = collect($responseObject->columns);
        $object->collection = new LengthAwarePaginator(
            ModelFactory::createCollection($this, $responseObject, $this->listModelClass),
            $responseObject->info->filtered,
            $responseObject->info->limit,
            $responseObject->info->offset / $responseObject->info->limit + 1,
        );

        return $object;
    }

    public function firstWhere(string $attribute, mixed $value): ?Model
    {
        $uriParameters = $this instanceof ChildClient ? [$this->parentId] : [];

        $filters = [$attribute => $value];

        $listModel = $this->getModels(
            uri: $this->listUri(),
            filters: $filters,
            uriParameters: $uriParameters
        )->firstWhere($attribute, $value);

        if (! isset($listModel->id)) {
            return null;
        }

        $uriParameters[] = $listModel->id;

        return $this->getModel($this->singleUri(), uriParameters: $uriParameters);
    }
}

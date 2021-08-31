<?php


namespace Flooris\ErgonodeApi\Api;

use stdClass;
use Flooris\ErgonodeApi\Connector;
use Flooris\ErgonodeApi\ErgonodeApi;
use InvalidArgumentException;
use Flooris\ErgonodeApi\Models\Contracts\Model;
use Illuminate\Support\Collection;
use Flooris\ErgonodeApi\Models\ModelFactory;
use Flooris\ErgonodeApi\Api\Contracts\Listable;
use Flooris\ErgonodeApi\Api\Contracts\Client;
use Flooris\ErgonodeApi\Api\Contracts\BaseClient;
use Flooris\ErgonodeApi\Api\Contracts\ChildClient;
use Flooris\ErgonodeApi\Models\Contracts\ListModel;
use Flooris\ErgonodeApi\Models\Contracts\BaseModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Flooris\ErgonodeApi\Models\Contracts\ChildModel;

abstract class AbstractBaseClient implements BaseClient
{
    protected string $modelClass;
    protected ?string $listModelClass;
    protected ErgonodeApi $ergonodeApi;

    public function __construct(?ErgonodeApi $ergonodeApi = null, ?string $modelClass = null, ?string $listModelClass = null)
    {
        $this->setErgonodeApi($ergonodeApi);
        $this->setModelClass($modelClass);
        $this->setListModelClass($listModelClass);
    }

    abstract public function modelClass(): string;

    abstract public function baseUri(): string;

    abstract public function singleUri(): string;

    private function setModelClass(?string $modelClass): void
    {
        $modelClassToSet = $modelClass ?? $this->modelClass();

        if (! is_a($modelClassToSet, BaseModel::class, true)) {
            throw new InvalidArgumentException("$modelClassToSet does not implement BaseModel interface.");
        }

        if (is_a($modelClassToSet, ListModel::class, true)) {
            throw new InvalidArgumentException("$modelClassToSet should not implement ListModel interface.");
        }

        $this->modelClass = $modelClassToSet;
    }

    private function setListModelClass(?string $listModelClass): void
    {
        if (! $this instanceof Listable) {
            $this->listModelClass = null;

            return;
        }

        $listModelClassToSet = $listModelClass ?? $this->listModelClass();

        if (! is_a($listModelClassToSet, BaseModel::class, true)) {
            throw new InvalidArgumentException("$listModelClassToSet does not implement BaseModel interface.");
        }

        if (! is_a($listModelClassToSet, ListModel::class, true)) {
            throw new InvalidArgumentException("$listModelClassToSet does not implement ListModel interface.");
        }

        $this->listModelClass = $listModelClassToSet;
    }

    private function setErgonodeApi(?ErgonodeApi $ergonodeApi = null): void
    {
        $this->ergonodeApi = $ergonodeApi ?? $this->resolveErgonodeApi();
    }

    public function getErgonodeApi(): ErgonodeApi
    {
        return $this->ergonodeApi;
    }

    private function resolveErgonodeApi(): ErgonodeApi
    {
        return app(ErgonodeApi::class);
    }

    public function find(string $id): Model|ChildModel
    {
        return $this->getModel($this->singleUri(), uriParameters: $this instanceof
                                                                  ChildClient ? [$this->parentId, $id] : [$id]);
    }

    protected function getModel(string $uri, array $query = [], array $uriParameters = []): Model|ChildModel
    {
        return ModelFactory::create($this, $this->getModelRaw($uri, $query, $uriParameters), $this->modelClass);
    }

    protected function getModelRaw(string $uri, array $query = [], array $uriParameters = []): stdClass
    {
        return $this->ergonodeApi->connector->get($uri, $query, $uriParameters);
    }

    protected function getModels(string $uri, array $columns = [], string $view = 'grid', ?int $limit = null, ?int $offset = null, array $filters = [], ?string $sortField = null, string $sortOrder = 'DESC', $uriParameters = []): Collection
    {
        return ModelFactory::createCollection($this, $this->getModelsRaw($uri, $columns, $view, $limit, $offset, $filters, $sortField, $sortOrder, $uriParameters), $this->listModelClass);
    }

    protected function getModelsPaginated(string $uri, array $columns = [], string $view = 'grid', ?int $perPage = null, int $currentPage = 1, array $filters = [], ?string $sortField = null, string $sortOrder = 'DESC', $uriParameters = []): LengthAwarePaginator
    {
        $offset         = $perPage ? ($currentPage - 1) * $perPage : null;
        $responseObject = $this->getModelsRaw($uri, $columns, $view, $perPage, $offset, $filters, $sortField, $sortOrder, $uriParameters);

        return new LengthAwarePaginator(
            ModelFactory::createCollection($this, $responseObject, $this->listModelClass),
            $responseObject->info->filtered,
            $responseObject->info->limit,
            $responseObject->info->offset / $responseObject->info->limit + 1,
        );
    }

    protected function getModelsRaw(string $uri, array $columns = [], string $view = 'grid', ?int $limit = null, ?int $offset = null, array $filters = [], ?string $sortField = null, string $sortOrder = 'DESC', $uriParameters = []): stdClass
    {
        if (! in_array($view, ['grid', 'list'])) {
            throw new InvalidArgumentException("The view argument can only be 'grid' or 'list'");
        }

        if (! in_array($sortOrder, ['DESC', 'ASC'])) {
            throw new InvalidArgumentException("The sortOrder argument can only be 'DESC' or 'ASC'");
        }

        $query = [
            'view'  => $view,
            'order' => $sortOrder,
        ];

        if ($limit) {
            $query['limit'] = $limit;
        }

        if ($offset) {
            $query['offset'] = $offset;
        }

        if ($sortField) {
            $query['field'] = $sortField;
        }

        if ($sortOrder) {
            $query['order'] = $sortOrder;
        }

        if (! empty($columns)) {
            $query['columns'] = implode(',', $columns);
        }

        if (! empty($filters)) {
            $query['filter'] = implode(';', collect($filters)
                ->map(fn (string $value, string $attribute) => "$attribute=$value")
                ->toArray());
        }

        return $this->ergonodeApi->connector->get($uri, $query, $uriParameters);
    }
}

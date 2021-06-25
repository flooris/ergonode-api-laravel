<?php

namespace Flooris\Ergonode\Models\Traits;

use stdClass;
use Flooris\Ergonode\Models\Contracts\Model;
use Illuminate\Pagination\LengthAwarePaginator;

trait ListableTrait
{
    public function list(array $columns = [], string $view = 'grid', ?int $perPage = null, int $currentPage = 1, array $filters = [], ?string $sortField = null, string $sortOrder = 'DESC'): LengthAwarePaginator
    {
        return $this->client->list($columns, $view, $perPage, $currentPage, $filters, $sortField, $sortOrder);
    }

    public function listWithColumns(array $columns = [], ?int $perPage = null, int $currentPage = 1, array $filters = [], ?string $sortField = null, string $sortOrder = 'DESC'): stdClass
    {
        return $this->client->listWithColumns($columns, $perPage, $currentPage, $filters, $sortField, $sortOrder);
    }

    public function firstWhere(string $attribute, mixed $value): ?Model
    {
        return $this->client->firstWhere($attribute, $value);
    }
}

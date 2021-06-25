<?php

namespace Flooris\ErgonodeApi\Models\Contracts;

use stdClass;
use Illuminate\Pagination\LengthAwarePaginator;

interface Listable
{
    public function list(array $columns = [], string $view = 'grid', ?int $perPage = null, int $currentPage = 1, array $filters = [], ?string $sortField = null, string $sortOrder = 'DESC'): LengthAwarePaginator;

    public function listWithColumns(array $columns = [], ?int $perPage = null, int $currentPage = 1, array $filters = [], ?string $sortField = null, string $sortOrder = 'DESC'): stdClass;

    public function firstWhere(string $attribute, mixed $value): ?Model;
}

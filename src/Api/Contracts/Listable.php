<?php

namespace Flooris\ErgonodeApi\Api\Contracts;

use stdClass;
use Flooris\ErgonodeApi\Models\Contracts\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface Listable
{
    public function listUri(): string;

    public function listModelClass(): string;

    public function list(array $columns = [], string $view = 'grid', ?int $perPage = null, int $currentPage = 1, array $filters = [], ?string $sortField = null, string $sortOrder = 'DESC'): LengthAwarePaginator;

    public function listWithColumns(array $columns = [], ?int $perPage = null, int $currentPage = 1, array $filters = [], ?string $sortField = null, string $sortOrder = 'DESC'): stdClass;

    public function firstWhere(string $attribute, mixed $value): ?Model;
}

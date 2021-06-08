<?php

namespace Flooris\ErgonodeApi\Attributes;

use JsonException;
use Illuminate\Support\Collection;
use Flooris\ErgonodeApi\ErgonodeApi;
use GuzzleHttp\Exception\GuzzleException;
use Flooris\ErgonodeApi\ErgonodeObjectApiAbstract;

class ProductListClient extends ErgonodeObjectApiAbstract
{
    public Collection $items;
    public Collection $columns;

    public function __construct(ErgonodeApi $connector, ?string $modelClass = null)
    {
        parent::__construct(
            $connector,
            ProductClient::ENDPOINT,
            $modelClass ?? ProductModel::class
        );
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function productsOverview(array $columns, int $page = 0, int $limit = 25, ?string $filter = null, ?string $sortField = null, ?string $sortOrder = null): Collection
    {
        $offset      = ($page > 0 ? ($limit * $page) : 0);
        $baseUri     = "{$this->getLocale()}/" . ProductClient::ENDPOINT;
        $this->items = collect();

        $requestParams = [
            'offset'   => $offset,
            'limit'    => $limit,
            'extended' => true,
            'columns'  => implode(',', $columns),
            'filter'   => $filter,
            'field'    => $sortField,
            'order'    => $sortOrder,
        ];
        $requestUri    = "$baseUri?" . http_build_query($requestParams);

        $result = json_decode($this->get($requestUri)
            ->getBody()
            ->getContents(), false, 512, JSON_THROW_ON_ERROR);

        $filteredCount = (int)$result->info->filtered;

        if (! $filteredCount || ! $result->collection) {
            $result = null;
        }

        if ($result) {
            $this->columns = collect($result->columns);

            foreach ($result->collection as $item) {
                $this->items->push((new $this->modelClass($this, $item)));
            }
        }

        return $this->items;
    }

    public function columnsOverview(): Collection
    {
        return $this->columns;
    }
}

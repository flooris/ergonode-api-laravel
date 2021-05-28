<?php

namespace Flooris\ErgonodeApi\Attributes;

use Illuminate\Support\Collection;
use Flooris\ErgonodeApi\ErgonodeApi;
use GuzzleHttp\Exception\GuzzleException;
use Flooris\ErgonodeApi\ErgonodeObjectApiAbstract;

class ProductListClient extends ErgonodeObjectApiAbstract
{
    public Collection $items;
    public Collection $columns;

    public function __construct(ErgonodeApi $connector)
    {
        return parent::__construct(
            $connector,
            ProductClient::ENDPOINT,
            ProductListItemModel::class
        );
    }

    /**
     * @param string      $locale
     * @param array       $columns
     * @param int         $page
     * @param int         $limit
     * @param string|null $filter
     * @return Collection
     * @throws GuzzleException
     */
    public function productsOverview(string $locale, array $columns, $page = 0, $limit = 25, ?string $filter = null, ?string $sortField = null, ?string $sortOrder = null): Collection
    {
        $offset      = ($page > 0 ? ($limit * $page) : 0);
        $baseUri     = "{$locale}/" . ProductClient::ENDPOINT;
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
        $requestUri    = "{$baseUri}?" . http_build_query($requestParams);

        $result = json_decode($this->get($requestUri)
            ->getBody()
            ->getContents());

        $filteredCount = (int)$result->info->filtered;

        if (! $filteredCount || ! $result->collection) {
            $result = null;
        }

        if ($result) {
            $this->columns = collect($result->columns);

            foreach ($result->collection as $item) {
                $this->items->push((new ProductListItemModel($this, $item, $locale)));
            }
        }

        return $this->items;
    }

    public function columnsOverview(): Collection
    {
        return $this->columns;
    }
}

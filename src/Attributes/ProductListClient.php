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
     * @param string|null $filter
     * @return Collection
     * @throws GuzzleException
     */
    public function productsOverview(string $locale, array $columns, ?string $filter = null): Collection
    {
        $page        = 0;
        $offset      = 0;
        $limit       = 25;
        $result      = null;
        $baseUri     = "{$locale}/" . ProductClient::ENDPOINT;
        $this->items = collect();

        while ($result || $page === 0) {
            $requestParams = [
                'offset'   => $offset,
                'limit'    => $limit,
                'extended' => true,
                'columns'  => implode(',', $columns),
                'filter'   => $filter,
            ];
            $requestUri    = "{$baseUri}?" . http_build_query($requestParams);

            $result = json_decode($this->get($requestUri)
                ->getBody()
                ->getContents());

            $filteredCount = (int)$result->info->filtered;
            $offset        = $limit * $page++;

            if (! $filteredCount || ! $result->collection) {
                $result = null;
            }

            if ($result) {
                $this->columns = collect($result->columns);

                foreach ($result->collection as $item) {
                    $this->items->push((new ProductListItemModel($this, $item, $locale)));
                }
            }
        }

        return $this->items;
    }
}
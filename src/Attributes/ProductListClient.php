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
     * @param array       $columns
     * @param string|null $filter
     * @return Collection
     * @throws GuzzleException
     * @throws JsonException
     */
    public function productsOverview(array $columns, ?string $filter = null): Collection
    {
        $page        = 0;
        $offset      = 0;
        $limit       = 25;
        $result      = null;
        $baseUri     = "{$this->getLocale()}/" . ProductClient::ENDPOINT;
        $this->items = collect();

        while ($result || $page === 0) {
            $requestParams = [
                'offset'   => $offset,
                'limit'    => $limit,
                'extended' => true,
                'columns'  => implode(',', $columns),
                'filter'   => $filter,
            ];
            $requestUri    = "$baseUri?" . http_build_query($requestParams);

            $result = json_decode($this->get($requestUri)
                ->getBody()
                ->getContents(),  false, 512, JSON_THROW_ON_ERROR);

            $filteredCount = (int)$result->info->filtered;
            $offset        = $limit * $page++;

            if (! $filteredCount || ! $result->collection) {
                $result = null;
            }

            if ($result) {
                $this->columns = collect($result->columns);

                foreach ($result->collection as $item) {
                    $this->items->push((new ProductModel($item)));
                }
            }
        }

        return $this->items;
    }

    public function columnsOverview(): Collection
    {
        return $this->columns;
    }
}

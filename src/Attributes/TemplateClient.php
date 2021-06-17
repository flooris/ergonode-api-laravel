<?php

namespace Flooris\ErgonodeApi\Attributes;

use JsonException;
use Flooris\ErgonodeApi\ErgonodeApi;
use GuzzleHttp\Exception\GuzzleException;
use Flooris\ErgonodeApi\ErgonodeObjectApiAbstract;

class TemplateClient extends ErgonodeObjectApiAbstract
{
    public const ENDPOINT = 'templates';

    public function __construct(ErgonodeApi $connector, ?string $modelClass = null, ?string $endpoint = null)
    {
        $endpoint = $endpoint ?? static::ENDPOINT;
        parent::__construct(
            $connector,
            $endpoint,
            $modelClass ?? TemplateModel::class
        );
    }

    /**
     * @param string $name
     * @return TemplateModel|null
     * @throws GuzzleException
     * @throws JsonException
     */
    public function findByName(string $name): ?TemplateModel
    {
        $itemCollection = $this->filter("name={$name}");

        $this->model = $itemCollection->first();

        return $this->model;
    }

    public function findByProductId($id): ?TemplateModel
    {
        return $this->find($id, 'template') ?? null;
    }
}

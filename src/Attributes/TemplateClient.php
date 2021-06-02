<?php

namespace Flooris\ErgonodeApi\Attributes;

use JsonException;
use Flooris\ErgonodeApi\ErgonodeApi;
use GuzzleHttp\Exception\GuzzleException;
use Flooris\ErgonodeApi\ErgonodeObjectApiAbstract;

class TemplateClient extends ErgonodeObjectApiAbstract
{
    public const ENDPOINT = 'templates';

    public function __construct(ErgonodeApi $connector, ?string $modelClass = null)
    {
        parent::__construct(
            $connector,
            static::ENDPOINT,
            $modelClass ?? TemplateModel::class
        );
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function findByName(string $name): ?TemplateModel
    {
        $itemCollection = $this->filter("name={$name}");

        $this->model = $itemCollection->first();

        return $this->model;
    }
}

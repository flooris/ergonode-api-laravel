<?php

namespace Flooris\ErgonodeApi\Attributes;

use Flooris\ErgonodeApi\ErgonodeApi;
use Flooris\ErgonodeApi\ErgonodeObjectApiAbstract;

class TemplateClient extends ErgonodeObjectApiAbstract
{
    const ENDPOINT = 'templates';

    public function __construct(ErgonodeApi $connector)
    {
        return parent::__construct(
            $connector,
            TemplateClient::ENDPOINT,
            TemplateModel::class
        );
    }

    public function findByName(string $locale, string $name)
    {
        $itemCollection = $this->filter($locale, "name={$name}");

        $this->model = $itemCollection->first();

        return (bool)$this->model;
    }

    public function model(): ?TemplateModel
    {
        return $this->model;
    }
}
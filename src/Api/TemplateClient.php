<?php

namespace Flooris\ErgonodeApi\Api;

use Flooris\ErgonodeApi\Api\Contracts\Listable;
use Flooris\ErgonodeApi\Api\Traits\ListableTrait;
use Flooris\ErgonodeApi\Models\TemplateListModel;
use Flooris\ErgonodeApi\Models\TemplateModel;

class TemplateClient extends AbstractClient implements Listable
{
    use ListableTrait;

    public function modelClass(): string
    {
        return TemplateModel::class;
    }

    public function listModelClass(): string
    {
        return TemplateListModel::class;
    }

    public function baseUri(): string
    {
        return 'templates';
    }

    public function singleUri(): string
    {
        return 'templates/%s';
    }

    public function listUri(): string
    {
        return $this->baseUri();
    }
}

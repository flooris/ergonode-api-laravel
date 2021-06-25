<?php

namespace Flooris\Ergonode\Api;

use Flooris\Ergonode\Api\Contracts\Listable;
use Flooris\Ergonode\Api\Traits\ListableTrait;
use Flooris\Ergonode\Models\TemplateListModel;
use Flooris\Ergonode\Models\TemplateModel;

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

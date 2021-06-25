<?php

namespace Flooris\Ergonode\Models;

use Flooris\Ergonode\Api\AttributeOptionClient;
use Flooris\Ergonode\Models\Contracts\Listable;
use Flooris\Ergonode\Models\Traits\ListableTrait;

class AttributeOptionBaseModel extends AbstractChildModel implements Listable
{
    use ListableTrait;

    public string $code;
    public array $_links = [];

    public function clientClass(): string
    {
        return AttributeOptionClient::class;
    }

    public function getParentIdKey(): string
    {
        return 'attribute_id';
    }
}

<?php

namespace Flooris\ErgonodeApi\Models;

use Flooris\ErgonodeApi\Api\AttributeOptionClient;
use Flooris\ErgonodeApi\Models\Contracts\Listable;
use Flooris\ErgonodeApi\Models\Traits\ListableTrait;

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

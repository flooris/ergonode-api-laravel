<?php

namespace Flooris\Ergonode\Models;

use Flooris\Ergonode\Api\AttributeClient;
use Flooris\Ergonode\Models\Contracts\Listable;
use Flooris\Ergonode\Models\Traits\ListableTrait;

class AttributeBaseModel extends AbstractModel implements Listable
{
    use ListableTrait;

    public int $index;
    public string $code;
    public mixed $label;
    public string $type;
    public string $scope;
    public array $groups = [];

    public function clientClass(): string
    {
        return AttributeClient::class;
    }
}

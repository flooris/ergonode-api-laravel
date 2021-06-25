<?php

namespace Flooris\ErgonodeApi\Models;

use Flooris\ErgonodeApi\Api\AttributeClient;
use Flooris\ErgonodeApi\Models\Contracts\Listable;
use Flooris\ErgonodeApi\Models\Traits\ListableTrait;

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

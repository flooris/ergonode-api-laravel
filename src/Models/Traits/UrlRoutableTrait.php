<?php

namespace Flooris\ErgonodeApi\Models\Traits;

use Flooris\ErgonodeApi\Models\Contracts\Model;
use Flooris\ErgonodeApi\Models\Contracts\ChildModel;

trait UrlRoutableTrait
{
    public function getRouteKey(): mixed
    {
        return $this->{$this->getRouteKeyName()};
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function resolveRouteBinding($value, $field = null): null|Model|ChildModel
    {
        $routeKeyName = $this->getRouteKeyName();

        return $routeKeyName === 'id' ? $this->find($value) : $this->firstWhere($field ?? $routeKeyName, $value);
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}

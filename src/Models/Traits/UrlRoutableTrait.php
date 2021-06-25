<?php


namespace Flooris\Ergonode\Models\Traits;


use Flooris\Ergonode\Models\Model;

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

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $this->firstWhere($field ?? $this->getRouteKeyName(), $value);
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}

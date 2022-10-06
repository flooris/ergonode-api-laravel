<?php

namespace Flooris\ErgonodeApi\Models;

use Illuminate\Support\Collection;

class AttributeObjectModel extends BaseObjectModel
{
    public function id(): string
    {
        return $this->raw['id'];
    }

    public function groups(): array
    {
        return $this->raw['groups'];
    }

    public function options(): array
    {
        return $this->raw['options'];
    }

    public function optionIdCollection(): Collection
    {
        $optionIdCollection = collect();

        foreach ($this->options() as $option) {
            $optionIdCollection->push((int)$option['id']);
        }

        return $optionIdCollection;
    }

    public function type(): string
    {
        return $this->raw['type'];
    }

    public function code(): string
    {
        return $this->raw['code'];
    }

    public function scope(): string
    {
        return $this->raw['scope'];
    }

    public function label(?string $localeIso = null): ?string
    {
        return $this->getStringValueByLocale('label', $localeIso);
    }

    public function hint(?string $localeIso = null): ?string
    {
        return $this->getStringValueByLocale('hint', $localeIso);
    }
}

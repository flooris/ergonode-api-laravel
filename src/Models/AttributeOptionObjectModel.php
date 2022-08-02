<?php

namespace Flooris\ErgonodeApi\Models;

class AttributeOptionObjectModel extends BaseObjectModel
{
    public function id(): string
    {
        return $this->raw['id'];
    }

    public function code(): string
    {
        return $this->raw['code'];
    }

    public function attributeId(): string
    {
        return $this->raw['attribute_id'];
    }

    public function label(?string $localeIso = null): ?string
    {
        return $this->getStringValueByLocale('label', $localeIso);
    }
}

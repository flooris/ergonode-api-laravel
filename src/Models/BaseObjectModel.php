<?php

namespace Flooris\ErgonodeApi\Models;

class BaseObjectModel
{
    public function __construct(
        protected array  $raw,
        protected string $defaultLocale,
    )
    {
    }

    protected function getStringValueByLocale(string $fieldName, ?string $localeIso = null): ?string
    {
        if (! $localeIso) {
            // Fallback to default translation
            $localeIso = $this->defaultLocale;
        }

        if (! is_array($this->raw[$fieldName])) {
            return $this->raw[$fieldName];
        }

        if (! isset($this->raw[$fieldName][$localeIso])) {
            if (isset($this->raw[$fieldName][$this->defaultLocale])) {
                // Fallback to default translation
                return $this->raw[$fieldName][$this->defaultLocale];
            }

            // Get first available translation
            return reset($this->raw[$fieldName]);
        }

        // Get the requested translation
        return $this->raw[$fieldName][$localeIso];
    }
}

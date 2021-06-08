<?php

namespace Flooris\ErgonodeApi\Attributes;

use stdClass;
use Illuminate\Support\Collection;

abstract class ErgonodeAbstractModel implements ErgonodeModel
{
    public string $locale;

    public function __construct(private ?ErgonodeClient $ergonodeClient = null, public ?stdClass $responseObject = null)
    {
        if (! $this->ergonodeClient) {
            $this->ergonodeClient = $this->resolveErgonodeClient();
        }

        $this->locale = $this->ergonodeClient->getLocale();

        if ($responseObject && !empty((array)$responseObject)) {
            $this->handleResponseObject();
        }
    }

    abstract protected function resolveErgonodeClient(): ErgonodeClient;
    abstract protected function handleResponseObject(): void;

    public function getErgonodeClient(): ErgonodeClient
    {
        return $this->ergonodeClient;
    }

    public static function find(string|int $value): static
    {
        return (new static)->getErgonodeClient()->find($value);
    }

    public static function all(): Collection
    {
        return (new static)->getErgonodeClient()->all();
    }
}
<?php

namespace Flooris\Ergonode\Models;

use Exception;
use Flooris\Ergonode\Api\Contracts\ChildClient;
use Flooris\Ergonode\Models\Contracts\ChildModel;


abstract class AbstractChildModel extends AbstractBaseModel implements ChildModel
{
    public function __construct(protected ?string $parentId = null, null|ChildClient $client = null)
    {
        $this->{$this->getParentIdKey()} = $parentId;

        parent::__construct($client);
    }

    protected function resolveClient(): ChildClient
    {
        $clientClass = $this->clientClass();

        if (! is_a($clientClass, ChildClient::class, true)) {
            throw new Exception("$clientClass does not implement ChildClient interface.");
        }

        if (! $parentId = $this->getParentId()) {
            throw new Exception("Can't instantiate ChildClient, either a resolved ChildClient or parent ID should be given in the constructor");
        }

        return new $clientClass($parentId);
    }

    public function getParentId(): ?string
    {
        return $this->{$this->getParentIdKey()} ?? $this->parentId ?? null;
    }

    abstract public function getParentIdKey(): string;
}

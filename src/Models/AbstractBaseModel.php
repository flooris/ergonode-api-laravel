<?php


namespace Flooris\Ergonode\Models;

use Flooris\Ergonode\Api\Contracts\Client;
use Flooris\Ergonode\Models\Contracts\Model;
use Flooris\Ergonode\Api\Contracts\ChildClient;
use Flooris\Ergonode\Models\Contracts\BaseModel;
use Flooris\Ergonode\Models\Contracts\ChildModel;

abstract class AbstractBaseModel implements BaseModel
{
    protected ChildClient|Client $client;

    public string $id;

    public function __construct(null|Client|ChildClient $client = null)
    {
        $this->client = $client ?? $this->resolveClient();
    }

    abstract public function clientClass(): string;

    abstract protected function resolveClient(): Client|ChildClient;

    public function getClient(): Client|ChildClient
    {
        return $this->client;
    }

    public function find(string $id): Model|ChildModel
    {
        return $this->client->find($id);
    }
}

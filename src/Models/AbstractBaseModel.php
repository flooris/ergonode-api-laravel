<?php


namespace Flooris\ErgonodeApi\Models;

use Flooris\ErgonodeApi\Api\Contracts\Client;
use Flooris\ErgonodeApi\Models\Contracts\Model;
use Flooris\ErgonodeApi\Api\Contracts\ChildClient;
use Flooris\ErgonodeApi\Models\Contracts\BaseModel;
use Flooris\ErgonodeApi\Models\Contracts\ChildModel;

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

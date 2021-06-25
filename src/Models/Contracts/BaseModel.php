<?php

namespace Flooris\ErgonodeApi\Models\Contracts;

use Flooris\ErgonodeApi\Api\Contracts\Client;
use Flooris\ErgonodeApi\Api\Contracts\ChildClient;

interface BaseModel
{
    public function clientClass(): string;
    public function getClient(): Client|ChildClient;
    public function find(string $id): Model|ChildModel;
}

<?php

namespace Flooris\Ergonode\Models\Contracts;

use Flooris\Ergonode\Api\Contracts\Client;
use Flooris\Ergonode\Api\Contracts\ChildClient;

interface BaseModel
{
    public function clientClass(): string;
    public function getClient(): Client|ChildClient;
    public function find(string $id): Model|ChildModel;
}

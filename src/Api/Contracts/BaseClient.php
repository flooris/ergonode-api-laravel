<?php

namespace Flooris\Ergonode\Api\Contracts;

use Flooris\Ergonode\ErgonodeApi;
use Flooris\Ergonode\Models\Contracts\Model;

interface BaseClient
{
    public function modelClass(): string;
    public function baseUri(): string;
    public function singleUri(): string;
    public function find(string $id): Model;
    public function getErgonodeApi(): ErgonodeApi;
}

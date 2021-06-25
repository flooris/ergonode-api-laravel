<?php

namespace Flooris\ErgonodeApi\Api\Contracts;

use Flooris\ErgonodeApi\ErgonodeApi;
use Flooris\ErgonodeApi\Models\Contracts\Model;

interface BaseClient
{
    public function modelClass(): string;
    public function baseUri(): string;
    public function singleUri(): string;
    public function find(string $id): Model;
    public function getErgonodeApi(): ErgonodeApi;
}

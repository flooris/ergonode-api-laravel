<?php

namespace Flooris\ErgonodeApi\Api\Contracts;

use Flooris\ErgonodeApi\ErgonodeApi;
use Flooris\ErgonodeApi\Models\Contracts\Model;
use Flooris\ErgonodeApi\Models\Contracts\ChildModel;

interface BaseClient
{
    public function modelClass(): string;
    public function baseUri(): string;
    public function singleUri(): string;
    public function find(string $id): Model|ChildModel;
    public function getErgonodeApi(): ErgonodeApi;
}

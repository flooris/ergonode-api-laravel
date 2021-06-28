<?php

namespace Flooris\ErgonodeApi\Models\Contracts;

interface ListModel
{
    public function getFullModel(): Model;
    public function modelClass(): string;
}

<?php

namespace Flooris\ErgonodeApi\Models\Contracts;

interface ListModel
{
    public function getFullModel();
    public function modelClass(): string;
}

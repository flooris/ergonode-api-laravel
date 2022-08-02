<?php

namespace Flooris\ErgonodeApi\Models;

use Flooris\ErgonodeApi\Api\Contracts\Client;
use Flooris\ErgonodeApi\Api\MultimediaClient;
use Flooris\ErgonodeApi\Models\Contracts\ListModel;

class MultimediaModel extends AbstractModel
{
    public function clientClass(): string
    {
        return MultimediaClient::class;
    }

    protected function resolveClient(): Client
    {
        $clientClass = $this->clientClass();

        if (! is_a($clientClass, Client::class, true)) {
            throw new \Exception("$clientClass does not implement Client interface.");
        }

        $modelClass     = static::class;
        $listModelClass = null;

        if ($this instanceof ListModel) {
            $modelClass     = null;
            $listModelClass = static::class;
        }

        return new $clientClass(modelClass: $modelClass, listModelClass: $listModelClass);
    }
}

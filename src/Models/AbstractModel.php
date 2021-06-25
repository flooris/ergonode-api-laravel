<?php


namespace Flooris\Ergonode\Models;

use Exception;
use Flooris\Ergonode\Api\Contracts\Client;
use Flooris\Ergonode\Models\Contracts\ListModel;

abstract class AbstractModel extends AbstractBaseModel
{
    public function __construct(?Client $client = null)
    {
        parent::__construct($client);
    }

    protected function resolveClient(): Client
    {
        $clientClass = $this->clientClass();

        if (!is_a($clientClass, Client::class, true)) {
            throw new Exception("$clientClass does not implement Client interface.");
        }

        $modelClass = static::class;
        $listModelClass = null;

        if ($this instanceof ListModel) {
            $modelClass = null;
            $listModelClass = static::class;
        }


        return new $clientClass();
    }
}

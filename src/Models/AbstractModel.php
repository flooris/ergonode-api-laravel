<?php


namespace Flooris\ErgonodeApi\Models;

use Exception;
use Flooris\ErgonodeApi\Api\Contracts\Client;
use Flooris\ErgonodeApi\Models\Contracts\ListModel;
use Flooris\ErgonodeApi\Models\Contracts\Model;

abstract class AbstractModel extends AbstractBaseModel implements Model
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

        return new $clientClass(modelClass: $modelClass, listModelClass: $listModelClass);
    }
}

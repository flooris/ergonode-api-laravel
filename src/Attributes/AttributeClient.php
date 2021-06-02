<?php

namespace Flooris\ErgonodeApi\Attributes;

use JsonException;
use Flooris\ErgonodeApi\ErgonodeApi;
use GuzzleHttp\Exception\GuzzleException;
use Flooris\ErgonodeApi\ErgonodeObjectApiAbstract;

class AttributeClient extends ErgonodeObjectApiAbstract
{
    public const ENDPOINT = 'attributes';

    public function __construct(ErgonodeApi $connector, ?string $modelClass = null)
    {
        parent::__construct(
            $connector,
            static::ENDPOINT,
            $modelClass ?? AttributeModel::class
        );
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function findByCode(string $code): ?AttributeModel
    {
        $this->model = $this->all()->where('code', $code)->first();

        return $this->model;
    }
}

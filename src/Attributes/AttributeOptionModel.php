<?php

namespace Flooris\ErgonodeApi\Attributes;

use JsonException;
use Flooris\ErgonodeApi\ErgonodeApi;
use GuzzleHttp\Exception\GuzzleException;

class AttributeOptionModel extends ErgonodeAbstractChildModel
{
    public ?string $id;
    public ?string $code;
    public array $label;

    /**
     * @throws JsonException
     */
    protected function handleResponseObject(): void
    {
        $this->id    = $this->responseObject?->id;
        $this->code  = $this->responseObject?->code;
        $this->label = $this->responseObject?->label ? json_decode(json_encode($this->responseObject->label, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR) : [];
    }

    public function resolveErgonodeClient(): AttributeOptionClient
    {
        return new AttributeOptionClient($this->parentModel->getErgonodeClient()->getErgonodeApi(), $this->parentModel, static::class);
    }

    public function update(): void
    {
        $body = [
            'code'  => $this->code,
            'label' => $this->label,
        ];

        $this->getErgonodeClient()->update($this->id, $body);
    }
}
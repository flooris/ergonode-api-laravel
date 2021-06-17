<?php

namespace Flooris\ErgonodeApi\Attributes;

use JsonException;

class AttributeOptionModel extends ErgonodeAbstractChildModel
{
    public ?string $id;
    public ?string $code;
    public string|array $label;

    /**
     * @throws JsonException
     */
    protected function handleResponseObject(): void
    {
        $this->id    = $this->responseObject->id;
        $this->code  = $this->responseObject->code;
        $this->label = (array)$this->responseObject->label;
    }

    public function resolveErgonodeClient(): AttributeOptionClient
    {
        return new AttributeOptionClient($this->parentModel->getErgonodeClient()
            ->getErgonodeApi(), $this->parentModel, static::class);
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
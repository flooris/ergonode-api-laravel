<?php

namespace Flooris\ErgonodeApi\Attributes;

use JsonException;
use Illuminate\Support\Collection;
use Flooris\ErgonodeApi\ErgonodeApi;
use GuzzleHttp\Exception\GuzzleException;

class AttributeModel extends ErgonodeAbstractModel
{
    public ?string $id;
    public ?string $code;
    public ?string $label;
    public array $groups;

    protected function handleResponseObject(): void
    {
            $this->id             = $this->responseObject?->id;
            $this->code           = $this->responseObject?->code;
            $this->groups         = $this->responseObject?->groups ?? [];

            if (is_array($this->responseObject?->label)) {
                $this->label = $this->responseObject->label[$this->locale] ?? '';
            } else if (is_object($this->responseObject?->label)) {
                $this->label = $this->responseObject->label->locale ?? '';
            } else {
                $this->label = $this->responseObject?->label;
            }
    }

    public function resolveErgonodeClient(): AttributeClient
    {
        return app(ErgonodeApi::class)->attributes(static::class);
    }

    public function getAttributeOptionClient(): AttributeOptionClient
    {
        return new AttributeOptionClient($this->getErgonodeClient()->getErgonodeApi(), $this);
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function options(): Collection
    {
        $attributeOptionClient = $this->getAttributeOptionClient();

        return $attributeOptionClient->all();
    }

    /**
     * @throws GuzzleException
     */
    public function createOption($code, $labelTranslations): void
    {
        $body = [
            'code'  => $code,
            'label' => $labelTranslations,
        ];

        $attributeOptionClient = $this->getAttributeOptionClient();
        $attributeOptionClient->create($body);
    }
}

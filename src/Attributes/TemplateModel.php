<?php

namespace Flooris\ErgonodeApi\Attributes;

use JsonException;
use Illuminate\Support\Collection;
use Flooris\ErgonodeApi\ErgonodeApi;
use GuzzleHttp\Exception\GuzzleException;

class TemplateModel extends ErgonodeAbstractModel
{
    public ?string $id;
    public ?string $name;
    public ?array $elements;
    public Collection $attributes;

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    protected function handleResponseObject(): void
    {
        $this->id       = $this->responseObject->id;
        $this->name     = $this->responseObject->name;
        $this->elements = $this->responseObject->elements;
        $this->setAttributes($this->locale, $this->elements);
    }

    public function resolveErgonodeClient(): TemplateClient
    {
        return app(ErgonodeApi::class)->templates(static::class);
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    private function setAttributes(string $locale, array $elements): void
    {
        $this->attributes = collect();
        foreach ($elements as $element) {
            if ($element->properties->type !== 'attribute') {
                continue;
            }

            $attributeId = $element->properties->attribute_id;

            $attributeClient = new AttributeClient($this->getErgonodeClient()->getErgonodeApi());
            if ($attributeClient->find($attributeId)) {
                $this->attributes->push($attributeClient->model());
            }
        }
    }
}

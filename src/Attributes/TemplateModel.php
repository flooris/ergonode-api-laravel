<?php

namespace Flooris\ErgonodeApi\Attributes;

use Illuminate\Support\Collection;

class TemplateModel
{
    private TemplateClient $client;
    public string $locale;
    public \stdClass $responseObject;
    public string $id;
    public string $name;
    public array $elements;
    public Collection $attributes;

    public function __construct(TemplateClient $client, \stdClass $responseObject, string $locale)
    {
        $this->client         = $client;
        $this->responseObject = $responseObject;
        $this->locale         = $locale;
        $this->id             = $responseObject->id;
        $this->name           = $responseObject->name;
        $this->elements       = $responseObject->elements;

        $this->setAttributes($locale, $this->elements);
    }

    private function setAttributes(string $locale, array $elements)
    {
        $this->attributes = collect();
        foreach ($elements as $element) {
            if ($element->properties->type !== 'attribute') {
                continue;
            }

            $attributeId = $element->properties->attribute_id;

            $attributeClient = new AttributeClient($this->client->getErgonodeApi());
            if ($attributeClient->find($locale, $attributeId)) {
                $this->attributes->push($attributeClient->model());
            }
        }
    }
}
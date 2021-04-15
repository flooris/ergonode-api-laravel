<?php

namespace Flooris\ErgonodeApi\Attributes;

use Illuminate\Support\Collection;

class AttributeModel
{
    private AttributeClient $client;
    public string $locale;
    public \stdClass $responseObject;
    public string $id;
    public string $code;
    public string $label;
    public array $groups;

    public function __construct(AttributeClient $client, \stdClass $responseObject, string $locale)
    {
        $this->client         = $client;
        $this->responseObject = $responseObject;
        $this->locale         = $locale;
        $this->id             = $responseObject->id;
        $this->code           = $responseObject->code;
        $this->groups         = $responseObject->groups;

        if (is_array($responseObject->label)) {
            $this->label = $responseObject->label[$locale] ?? '';
        } else if (is_object($responseObject->label)) {
            $this->label = $responseObject->label->$locale ?? '';
        } else {
            $this->label = $responseObject->label;
        }
    }

    public function getAttributeOptionClient(): AttributeOptionClient
    {
        return new AttributeOptionClient($this->client->getErgonodeApi(), $this);
    }

    public function options(string $locale): Collection
    {
        $attributeOptionClient = $this->getAttributeOptionClient();

        return $attributeOptionClient->all($locale);
    }

    public function createOption($code, $labelTranslations)
    {
        $body = [
            'code'  => $code,
            'label' => $labelTranslations,
        ];

        $attributeOptionClient = $this->getAttributeOptionClient();
        $attributeOptionClient->create($this->locale, $body);
    }
}
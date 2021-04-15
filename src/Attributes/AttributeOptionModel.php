<?php

namespace Flooris\ErgonodeApi\Attributes;

class AttributeOptionModel
{
    private AttributeOptionClient $client;
    public string $locale;
    public \stdClass $responseObject;
    public string $id;
    public string $code;
    public array $label;

    public function __construct(AttributeOptionClient $client, \stdClass $responseObject, string $locale)
    {
        $this->client         = $client;
        $this->responseObject = $responseObject;
        $this->locale         = $locale;
        $this->id             = $responseObject->id;
        $this->code           = $responseObject->code;
        $this->label          = json_decode(json_encode($responseObject->label), true);
    }

    public function update()
    {
        $body = [
            'code'  => $this->code,
            'label' => $this->label,
        ];

        $this->client->update($this->locale, $this->id, $body);
    }
}
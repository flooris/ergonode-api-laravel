<?php


namespace Flooris\ErgonodeApi\Attributes;


class MultimediaModel
{
    private MultimediaClient $client;
    public string $locale;
    public string $id;
    public string $name;
    public string $extension;

    public function __construct(MultimediaClient $client, \stdClass $responseObject, string $locale)
    {
        $this->id        = $responseObject->id;
        $this->client    = $client;
        $this->locale    = $locale;
        $this->name      = $responseObject->name;
        $this->extension = $responseObject->extension;
    }
}
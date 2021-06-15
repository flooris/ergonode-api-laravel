<?php


namespace Flooris\ErgonodeApi\Attributes;


use Flooris\ErgonodeApi\ErgonodeApi;

class MultimediaModel extends ErgonodeAbstractModel
{
    private MultimediaClient $client;
    public string $locale;
    public string $id;
    public string $name;
    public string $extension;

    public function __construct(MultimediaClient $client, \stdClass $responseObject)
    {
        parent::__construct($client, $responseObject);
    }

    protected function handleResponseObject(): void
    {
        if ($this->responseObject){
            $this->id        = $this->responseObject->id;
            $this->name      = $this->responseObject->name;
            $this->extension = $this->responseObject->extension;
        }
        // TODO: Implement handleResponseObject() method.
    }

    protected function resolveErgonodeClient(): ErgonodeClient
    {
        // TODO: Implement resolveErgonodeClient() method.
        return app(ErgonodeApi::class)->template(static::class);
    }
}
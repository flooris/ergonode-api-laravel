<?php

namespace Flooris\ErgonodeApi\Attributes;

use Illuminate\Support\Collection;

class ProductModel
{
    private ProductClient $client;
    public string $locale;
    public \stdClass $responseObject;
    public string $id;
    public string $type;
    public string $sku;
    public string $template_id;
    public string $design_template_id;
    public array $attributes;

    public function __construct(ProductClient $client, \stdClass $responseObject, string $locale)
    {
        $this->client             = $client;
        $this->responseObject     = $responseObject;
        $this->locale             = $locale;
        $this->id                 = $responseObject->id;
        $this->type               = $responseObject->type;
        $this->sku                = $responseObject->sku;
        $this->template_id        = $responseObject->template_id;
        $this->design_template_id = $responseObject->design_template_id;
        $this->attributes         = json_decode(json_encode($responseObject->attributes), true);
    }

    public function template(): TemplateModel
    {
        $templateClient = new TemplateClient($this->client->getErgonodeApi());
        $templateClient->find($this->locale, $this->template_id);

        return $templateClient->model();
    }
}
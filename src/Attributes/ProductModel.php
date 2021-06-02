<?php

namespace Flooris\ErgonodeApi\Attributes;

use stdClass;
use JsonException;
use Illuminate\Support\Collection;
use Flooris\ErgonodeApi\ErgonodeApi;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Routing\UrlRoutable;

class ProductModel extends ErgonodeAbstractModel implements UrlRoutable
{
    protected string $primaryKey = 'id';

    public ?string $id;
    public ?string $type;
    public ?string $sku;
    public ?string $template_id;
    public ?string $design_template_id;
    public array $attributes;
    private Collection $attributeOptions;

    public function __construct(?ErgonodeClient $ergonodeClient = null, ?stdClass $responseObject = null)
    {
        parent::__construct($ergonodeClient, $responseObject);
        $this->attributeOptions = collect();
    }

    /**
     * @throws JsonException
     */
    protected function handleResponseObject(): void
    {
        $this->id                 = $this->responseObject?->id;
        $this->type               = $this->responseObject?->type;
        $this->sku                = $this->responseObject?->sku;
        $this->template_id        = $this->responseObject?->template_id;
        $this->design_template_id = $this->responseObject?->design_template_id;
        $this->attributes         = json_decode(
            json_encode($this->responseObject->attributes ?? [], JSON_THROW_ON_ERROR),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    public function resolveErgonodeClient(): ProductClient
    {
        return app(ErgonodeApi::class)->products(static::class);
    }

    public function template(): ?TemplateModel
    {
        return $this->getErgonodeClient()
            ->getErgonodeApi()
            ->templates()
            ->find($this->locale, $this->template_id);
    }

    public function addAttribute(AttributeModel $attribute, ?AttributeOptionModel $attributeOption, string $label, string $locale = 'en_GB'): void
    {
        $this->attributeOptions->push([
            'attribute'       => $attribute,
            'attributeOption' => $attributeOption,
            'label'           => $label,
            'locale'          => $locale,
        ]);
    }

    public function saveAttributes(): void
    {
        $body = [
            'data' => [],
        ];

        $attributes = [];

        $this->attributeOptions->each(function ($item) use (&$attributes) {

            $isSelect = (bool)$item['attributeOption'];

            $values = [];

            if ($isSelect) {
                $values[] = [
                    'language' => $item['locale'],
                    'value'    => $item['attributeOption']->id,
                ];
            } else {
                $values[] = [
                    'language' => $item['locale'],
                    'value'    => $item['label'],
                ];
            }

            $attributes[] = [
                'id'     => $item['attribute']->id,
                'values' => $values,
            ];
        });


        $body['data'][] = [
            'id'      => $this->id,
            'payload' => $attributes,
        ];

        $this->getErgonodeClient()->append( 'attributes', $body);
    }

    public function getKeyName(): string
    {
        return $this->primaryKey;
    }

    public function getKey(): mixed
    {
        return $this->getAttribute($this->getKeyName());
    }

    public function getRouteKeyName(): string
    {
        return $this->getKeyName();
    }

    public function getRouteKey(): mixed
    {
        return $this->getAttribute($this->getRouteKeyName());
    }

    public function getAttribute($key): mixed
    {
        return $this->{$key} ?? null;
    }

    public function resolveRouteBinding(mixed $value, $field = null): ?static
    {
        return $this->getErgonodeClient()->firstWhere($field ?? $this->getRouteKeyName(), $value);
    }

    public function resolveChildRouteBinding($childType, $value, $field): void
    {

    }
}

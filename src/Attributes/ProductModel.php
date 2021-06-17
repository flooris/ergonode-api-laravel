<?php

namespace Flooris\ErgonodeApi\Attributes;

use stdClass;
use JsonException;
use Illuminate\Support\Collection;
use Flooris\ErgonodeApi\ErgonodeApi;
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
    private $template;
    private ?array $columns;

    public function __construct(?ErgonodeClient $ergonodeClient = null, ?stdClass $responseObject = null, array $columns = null)
    {
        $this->columns = $columns;
        parent::__construct($ergonodeClient, $responseObject);
        $this->attributeOptions = collect();
    }

    /**
     * @throws JsonException
     */
    protected function handleResponseObject(): void
    {
        $this->id   = is_object($this->responseObject->id) ? $this->responseObject->id->value : $this->responseObject->id;
        $this->type = $this->responseObject->type ?? null;
        $this->sku  = is_object($this->responseObject->sku) ? $this->responseObject->sku->value : $this->responseObject->sku;

        if (isset($this->responseObject->template_id)) {
            $this->template_id        = $this->responseObject->template_id ?? null;
            $this->design_template_id = $this->responseObject->design_template_id ?? null;
            $attributes               = json_decode(
                json_encode($this->responseObject->attributes ?? [], JSON_THROW_ON_ERROR),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
            $this->mapAttributesOnProduct($attributes);
        }
    }

    private function mapAttributesOnProduct(array $attributes)
    {
        $locale           = $this->locale;
        $this->attributes = collect($attributes)->map(function ($attribute) use ($locale) {
            if (isset($attribute[$locale])) {
                return $attribute[$locale];
            }
            if (gettype($attribute) == "array" && count($attribute) == 1) {
                return collect($attribute)->first();
            }

            return $attribute;
        })->toArray();
    }

    public function addMissingData()
    {
        $data                     = $this->getErgonodeClient()->getErgonodeApi()->products()->find($this->id);
        $this->template_id        = $data->template_id;
        $this->design_template_id = $data->design_template_id;
        $this->attributes         = $data->attributes;
        $this->setTemplate();
        $this->formatAttributes();
    }

    public function formatAttributes(): void
    {
        $model                     = $this;
        $ergonodeApi               = $this->getErgonodeClient()->getErgonodeApi();
        $attributeModelsOnTemplate = $this->template->attributes;

        $this->attributes = collect($this->attributes)->filter(function ($value, $key) {
            return ! str_contains($key, "esa_");
        })->map(function ($attributeValue, $attributeCode) use ($attributeModelsOnTemplate, $model, $ergonodeApi) {
            $attributeModel = $attributeModelsOnTemplate->filter(function ($attribute) use ($attributeCode, $model, $ergonodeApi) {
                return $attribute->code == $attributeCode;
            })->first();

            if (! isset($attributeModel->options)) {
                $attributeModel->options = [];
            }
            $attributeModel->label = $attributeValue;
            $attributeModel->option();

            return $attributeModel;
        })->toArray();
    }

    public function resolveErgonodeClient(): ProductClient
    {
        return app(ErgonodeApi::class)->products(static::class);
    }

    private function setTemplate()
    {
        $this->template = $this->getErgonodeClient()
            ->getErgonodeApi()
            ->templates(null, 'products')
            ->findByProductId($this->id);
    }

    public function template()
    {
        return $this->template ?? null;
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

        $this->getErgonodeClient()->append('attributes', $body);
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

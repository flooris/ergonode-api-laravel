<?php

namespace Flooris\ErgonodeApi\Models;

use stdClass;
use Illuminate\Support\Collection;
use Flooris\ErgonodeApi\Models\Contracts\Model;
use Flooris\ErgonodeApi\Api\Contracts\BaseClient;
use Flooris\ErgonodeApi\Models\Contracts\ChildModel;

class ModelFactory
{
    public static function create(BaseClient $client, stdClass $responseObject, string $resourceName): Model|ChildModel
    {
        $resource = new $resourceName(client: $client);
        $result   = static::resolveLocaleValues($client->getErgonodeApi()->connector->getLocale(), $responseObject);

        foreach ($result as $property => $value) {
            $resource->{$property} = match (true) {
                $property === '_links' => collect($value)
                    ->mapWithKeys(fn (stdClass $link, string $key) => [$key => $link->href])
                    ->toArray(),
                $property === 'attributes' && $resource instanceof
                                              ProductModel => static::resolveProductModelAttributes($value, $resource, $result->id),
                default => $value,
            };
        }

        $defaultLocale = $resource->getClient()->getErgonodeApi()->connector->getLocale();

        if ($resource instanceof ProductModel && isset($resource->translations)) {
            static::resolveTranslationsOnProductResource($resource, $defaultLocale);
        }

        return $resource;
    }

    private static function resolveTranslationsOnProductResource(mixed $resource, string $defaultLocale): mixed
    {
        $resource->translations["attributes"] = (object)[];

        foreach ($resource->attributes as $field => $value) {
            $resource->translations["attributes"]->{$field} = (object)[
                $defaultLocale => $value,
            ];
        }

        $api             = $resource->getClient()->getErgonodeApi();
        $languagesClient = $api->languages();
        $languages       = $languagesClient->all(true);

        foreach ($languages as $language) {
            if ($defaultLocale === $language->code) {
                continue;
            }

            $resultInLanguage = $api->connector->get($api->products()->singleUri() .
                                                     "/inherited/$language->code", uriParameters: [$resource->id]);
            $resource->getClient()->getErgonodeApi()->connector->setLocale($language->code);


            $resultInLanguage = (object)collect(static::resolveLocaleValues($language->code, $resultInLanguage)->attributes)
                ->map(function (string|array $item): string {
                    if (is_array($item)) {
                        return implode(",", $item);
                    }

                    return $item;
                })
                ->toArray();

            $resolvedLanguage = static::resolveProductModelAttributes($resultInLanguage, $resource, $resource->id);
            foreach ($resolvedLanguage as $field => $value) {
                $resource->translations["attributes"]->{$field}->{$language->code} = $value;
            }
        }

        $resource->getClient()->getErgonodeApi()->connector->setLocale($defaultLocale);

        return $resource;
    }

    public static function createCollection(BaseClient $client, stdClass $responseObject, string $resourceName): Collection
    {
        $result    = static::resolveListColumns($responseObject);
        $resources = [];

        foreach ($result->collection as $entry) {
            $resources[] = static::create($client, $entry, $resourceName);
        }

        return collect($resources);
    }

    private static function resolveProductModelAttributes(stdClass $attributes, ProductModel $productModel, string $productId): array
    {
        $newAttributes = (array)$attributes;

        foreach ($productModel->template($productId)->elements as $element) {
            if (! isset($element->properties->attribute_code) ||
                ! array_key_exists($element->properties->attribute_code, $newAttributes) ||
                ! in_array($element->type, ['SELECT', 'MULTI_SELECT', 'TEXT', 'TEXT_AREA', 'PRICE', 'NUMERIC', 'IMAGE', 'GALLERY'])) {
                continue;
            }

            $key = $element->properties->attribute_code;

            if ($element->type === 'MULTI_SELECT' || $element->type === 'GALLERY') {
                $originalValues = explode(',', $newAttributes[$key]);
                $newValue       = [];

                foreach ($originalValues as $originalValue) {
                    if (isset($element->properties?->options)) {
                        $newValue[$originalValue] = $element->properties?->options?->{$originalValue}?->label;
                    } else {
                        $newValue[] = $originalValue;
                    }
                }

                $newValue = empty($newValue) ? null : $newValue;
            } else if (isset($element->properties?->options)) {
                $originalValue = $newAttributes[$key];
                $newValue      = $element->properties?->options?->{$originalValue}?->label;
            } else if (isset($newAttributes[$element->properties?->attribute_code])) {
                $newValue = $newAttributes[$key];
            } else {
                $originalValue = $newAttributes[$key];
                $newValue      = null;
            }

            if ($element->type === 'SELECT') {
                $newAttributes[$key] = [$originalValue => $newValue];
            } else if ($newValue) {
                $newAttributes[$key] = $newValue;
            }
        }

        return $newAttributes;
    }


    private static function resolveListColumns(stdClass $response): stdClass
    {
        if (! isset($response->columns)) {
            return $response;
        }

        foreach ($response->columns as $column) {
            if ($column->type === 'SELECT') {
                foreach ($response->collection as $item) {
                    $id = $item->{$column->id};

                    if ($id) {
                        $item->{$column->id} = $column->filter->options?->{$id}->label;
                    }
                }
            } elseif ($column->type === 'MULTI_SELECT') {
                foreach ($response->collection as $item) {
                    $ids    = $item->{$column->id};
                    $values = [];

                    foreach ($ids as $id) {
                        $values[] = $column->filter->options?->{$id}->label ?? $column->filter->options?->{$id}->code;
                    }

                    if (! empty($values)) {
                        $item->{$column->id} = $values;
                    }
                }
            }
        }

        return $response;
    }

    private static function resolveLocaleValues(string $locale, mixed $input)
    {
        if (is_object($input)) {
            foreach ($input as $key => &$value) {
                $value = match (true) {
                    str_starts_with($key, 'esa_') && ! property_exists($value, $locale) &&
                    is_object($value) => $value->{''},
                    is_object($value) && property_exists($value, $locale) => $value->{$locale},
                    is_object($value) || is_array($value) => static::resolveLocaleValues($locale, $value),
                    default => $value,
                };
            }
        } elseif (is_array($input)) {
            foreach ($input as &$item) {
                $item = static::resolveLocaleValues($locale, $item);
            }
        }

        return $input;
    }
}

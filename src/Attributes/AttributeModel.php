<?php

namespace Flooris\ErgonodeApi\Attributes;

use JsonException;
use Illuminate\Support\Collection;
use Flooris\ErgonodeApi\ErgonodeApi;
use GuzzleHttp\Exception\GuzzleException;

class AttributeModel extends ErgonodeAbstractModel
{
    public ?string $id;
    public ?string $code;
    public string|array|null $label;
    public array $options;
    public array $groups;
    public null|AttributeOptionModel|array $currentOption;

    protected function handleResponseObject(): void
    {
        $this->id      = $this->responseObject->id;
        $this->code    = $this->responseObject->code;
        $this->groups  = $this->responseObject->groups ?? [];
        $this->options = [];
        if (is_array($this->responseObject->label)) {
            $this->label = $this->responseObject->label[$this->locale] ?? '';
        } else if (is_object($this->responseObject->label)) {
            $this->label = $this->responseObject->label->locale ?? '';
        } else {
            $this->label = $this->responseObject->label;
        }
    }

    public function resolveErgonodeClient(): AttributeClient
    {
        return app(ErgonodeApi::class)->attributes(static::class);
    }

    public function getAttributeOptionClient(): AttributeOptionClient
    {
        return new AttributeOptionClient($this->getErgonodeClient()->getErgonodeApi(), $this);
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * @throws GuzzleException
     */
    public function createOption($code, $labelTranslations): void
    {
        $body = [
            'code'  => $code,
            'label' => $labelTranslations,
        ];

        $attributeOptionClient = $this->getAttributeOptionClient();
        $attributeOptionClient->create($body);
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public function option(): null|AttributeOptionModel|array|string
    {
        if (empty($this->options)) {
            $this->currentOption = null;

            return $this->label;
        }

        if (isset($this->currentOption) && $this->currentOption !== null) {
            return $this->currentOption;
        }

        $codes = collect(explode(',', $this->label));

        $this->setCurrentOption($codes);
        if (gettype($this->currentOption) == "array") {
            $this->label = collect($this->currentOption)->map(function ($option) {
                return $option->label[$this->locale];
            })->toArray();

            return $this->currentOption;
        }

        $this->label = $this->currentOption->label[$this->locale];

        return $this->currentOption;
    }

    public function setCurrentOption(Collection $optionCodes): void
    {
        $options = $optionCodes->map(function ($code) {
            return $this->getAttributeOptionClient()->findById($code);
        });

        if (count($options) == 1) {
            $this->currentOption = $options->first();
        }

        if (count($options) > 1) {
            $this->currentOption = $options->toArray();
        }
    }
}

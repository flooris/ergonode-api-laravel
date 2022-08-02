<?php

namespace Flooris\ErgonodeApi\Api;

use stdClass;
use Flooris\ErgonodeApi\Models\LanguageModel;

class LanguageClient extends AbstractClient
{

    public function modelClass(): string
    {
        return LanguageModel::class;
    }

    public function listUri(): string
    {
        return 'languages';
    }

    public function baseUri(): string
    {
        return 'languages';
    }

    public function singleUri(): string
    {
        return 'languages/%s';
    }

    public function all(bool $onlyInTree = false): array
    {
        return collect($this->getErgonodeApi()->connector->get($this->baseUri())->collection)
            ->map(function (stdClass $lang) {
                $model = new $this->modelClass();

                foreach ($lang as $field => $value) {
                    $model->{$field} = $value;
                }


                return $model;
            })->filter(fn ($language): bool => ($onlyInTree && $language->tree) || ! $onlyInTree)
            ->toArray();
    }
}
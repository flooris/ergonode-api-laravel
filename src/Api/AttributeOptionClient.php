<?php

namespace Flooris\ErgonodeApi\Api;

use Illuminate\Support\Collection;
use Flooris\ErgonodeApi\Models\ModelFactory;
use Flooris\ErgonodeApi\Api\Contracts\Listable;
use Flooris\ErgonodeApi\Api\Traits\ListableTrait;
use Flooris\ErgonodeApi\Api\Contracts\ChildClient;
use Flooris\ErgonodeApi\Models\AttributeOptionModel;
use Flooris\ErgonodeApi\Models\AttributeOptionListModel;

class AttributeOptionClient extends AbstractChildClient implements Listable
{
    use ListableTrait;

    public function modelClass(): string
    {
        return AttributeOptionModel::class;
    }

    public function baseUri(): string
    {
        return 'attributes/%s/options';
    }

    public function singleUri(): string
    {
        return 'attributes/%s/options/%s';
    }

    public function listUri(): string
    {
        return 'attributes/%s/options/grid';
    }

    public function getAllOptionsFull(): Collection
    {
        $rawOptions              = $this->getModelsRaw($this->baseUri(), uriParameters: [$this->parentId]);
        $optionsCollection       = collect($rawOptions->collection)->transform(fn ($option) => [
            'id'           => $option?->id,
            'code'         => $option?->code,
            'label'        => $option?->label,
            'attribute_id' => $this->parentId,
        ]);
        $optionsArrayWithObjects = json_decode(json_encode($optionsCollection->toArray()));

        return ModelFactory::createCollection($this, (object)['collection' => $optionsArrayWithObjects], AttributeOptionModel::class);
    }

    public function listModelClass(): string
    {
        return AttributeOptionListModel::class;
    }
}

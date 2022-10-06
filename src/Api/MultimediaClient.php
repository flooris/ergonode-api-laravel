<?php

namespace Flooris\ErgonodeApi\Api;

use Exception;
use Flooris\ErgonodeApi\Models\ModelFactory;
use Flooris\ErgonodeApi\Models\MultimediaModel;
use Flooris\ErgonodeApi\Models\Contracts\Model;
use Flooris\ErgonodeApi\Models\ProductListModel;
use Flooris\ErgonodeApi\Models\ProductModel;
use Flooris\ErgonodeApi\Api\Traits\ListableTrait;
use Flooris\ErgonodeApi\Api\Contracts\Listable;
use Flooris\ErgonodeApi\Models\TemplateModel;
use Flooris\ErgonodeApi\Api\Contracts\ChildClient;
use Flooris\ErgonodeApi\Models\MultimediaListModel;
use Flooris\ErgonodeApi\Models\Contracts\ChildModel;

class MultimediaClient extends AbstractClient implements Listable
{
    use ListableTrait;

    public function modelClass(): string
    {
        return MultimediaModel::class;
    }

    public function listModelClass(): string
    {
        return MultimediaListModel::class;
    }

    public function listUri(): string
    {
        return 'multimedia';
    }

    public function baseUri(): string
    {
        return 'multimedia';
    }

    public function singleUri(): string
    {
        return 'multimedia/%s';
    }

    public function downloadUri(): string
    {
        return 'multimedia/%s/download/default';
    }

    public function download(string $id): mixed
    {
        $uri = vsprintf($this->downloadUri(), [$id]);

        return $this->getErgonodeApi()->connector->getRawContent($uri);
    }

    public function update(string $id, array $attributes): MultimediaModel
    {
        $connector = $this->ergonodeApi->connector;

        try {
            $connector->put($this->singleUri(), $attributes, [], [$id], false);
        } catch (Exception $e) {
            //Catches the error that can not resolve the empty response, anything else will error out.
            if ($e->getCode() !== 4) {
                throw $e;
            }
        }

        return $this->find($id);
    }

    public function create($imageData, $name): MultimediaModel
    {
        $uploadedImage = $this->ergonodeApi->connector->upload("upload", $imageData);

        $connector = $this->ergonodeApi->connector;

        $response = $connector->post($this->baseUri(), [
            'name'    => $name,
            'imageId' => $uploadedImage->id,
        ]);

        return $this->find($uploadedImage->id);
    }
}

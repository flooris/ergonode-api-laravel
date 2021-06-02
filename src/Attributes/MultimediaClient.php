<?php


namespace Flooris\ErgonodeApi\Attributes;

use Flooris\ErgonodeApi\ErgonodeApi;
use Flooris\ErgonodeApi\ErgonodeObjectApiAbstract;

class MultimediaClient extends ErgonodeObjectApiAbstract
{
    const ENDPOINT = "multimedia";

    public function __construct(ErgonodeApi $connector)
    {
        parent::__construct($connector, self::ENDPOINT, MultimediaModel::class);
    }

    public function findImage(string $id, $locale = "en_GB")
    {
        $foundImage = $this->find($locale, $id);

        return (bool)$foundImage;
    }

    public function updateImage(string $id, array $imageData, $locale = "en_GB"): bool
    {
        return (bool)$this->update($locale, $id, [
            'name' => $imageData['file_name'],
        ]);
    }

    public function saveImage($imageData, $locale = "en_GB"): bool
    {
        $uploadedImage = $this->upload("upload", $imageData);
        $this->findImage($uploadedImage->id, $locale);

        return (bool)$uploadedImage;
    }
}
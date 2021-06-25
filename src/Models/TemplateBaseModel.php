<?php


namespace Flooris\Ergonode\Models;

use Flooris\Ergonode\Api\TemplateClient;
use Flooris\Ergonode\Models\Contracts\Listable;
use Flooris\Ergonode\Models\Traits\ListableTrait;

class TemplateBaseModel extends AbstractModel implements Listable
{
    use ListableTrait;

    public string $name;
    public array $_links = [];
    public ?string $image_id;
    public string $group_id;

    public function clientClass(): string
    {
        return TemplateClient::class;
    }
}

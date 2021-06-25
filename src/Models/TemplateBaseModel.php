<?php


namespace Flooris\ErgonodeApi\Models;

use Flooris\ErgonodeApi\Api\TemplateClient;
use Flooris\ErgonodeApi\Models\Contracts\Listable;
use Flooris\ErgonodeApi\Models\Traits\ListableTrait;

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

<?php


namespace Flooris\ErgonodeApi\Models;

use Flooris\ErgonodeApi\Api\LanguageClient;
use Flooris\ErgonodeApi\Models\Traits\ListableTrait;

class LanguageBaseModel extends AbstractModel
{
    use ListableTrait;

    public string $id;
    public string $name;
    public string $code;
    public bool $tree;

    public function clientClass(): string
    {
        return LanguageClient::class;
    }
}

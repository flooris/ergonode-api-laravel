<?php


namespace Flooris\ErgonodeApi\Models;


use Flooris\ErgonodeApi\Models\Traits\ListModelTrait;

class TemplateListModel extends TemplateBaseModel
{
    use ListModelTrait;

    public string $default_label_attribute;
    public string $default_image_attribute;

    public function modelClass(): string
    {
        return TemplateModel::class;
    }
}

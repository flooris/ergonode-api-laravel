<?php


namespace Flooris\Ergonode\Models;


use Flooris\Ergonode\Models\Traits\ListModelTrait;

class TemplateListModel extends TemplateBaseModel
{
    use ListModelTrait;

    public string $default_label_attribute;
    public string $default_image_attribute;
}

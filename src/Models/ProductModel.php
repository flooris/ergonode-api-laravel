<?php

namespace Flooris\ErgonodeApi\Models;

class ProductModel extends ProductBaseModel
{
    public string $type;
    public array $attributes;
    public array $categories   = [];
    public array $translations = [];
    public string $template_id;
    public string $design_template_id;
}

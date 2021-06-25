<?php

namespace Flooris\Ergonode\Models;

class ProductModel extends ProductBaseModel
{
    public string $type;
    public array $attributes;
    public array $categories = [];
    public string $template_id;
    public string $design_template_id;
}

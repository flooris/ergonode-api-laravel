<?php

namespace Flooris\ErgonodeApi\Models;

class AttributeModel extends AttributeBaseModel
{
    public array $options = [];
    public array $translations = [];
    public mixed $hint;
    public mixed $placeholder;
}

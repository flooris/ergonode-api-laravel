<?php

namespace Flooris\Ergonode\Models\Contracts;

use Flooris\Ergonode\Api\Contracts\ChildClient;

interface ChildModel extends BaseModel
{
    public function __construct(?string $parentId = null, ?ChildClient $client = null);
    public function getParentId(): ?string;
    public function getParentIdKey(): string;
}

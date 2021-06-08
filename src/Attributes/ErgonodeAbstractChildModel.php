<?php


namespace Flooris\ErgonodeApi\Attributes;


use stdClass;
use Illuminate\Support\Collection;

abstract class ErgonodeAbstractChildModel extends ErgonodeAbstractModel
{
    public function __construct(public ErgonodeModel $parentModel, ?ErgonodeClient $ergonodeClient = null, ?stdClass $responseObject = null)
    {
        parent::__construct($ergonodeClient, $responseObject);
    }
}
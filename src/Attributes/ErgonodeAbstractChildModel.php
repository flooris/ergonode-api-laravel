<?php


namespace Flooris\ErgonodeApi\Attributes;


use stdClass;

abstract class ErgonodeAbstractChildModel extends ErgonodeAbstractModel
{
    public function __construct(?ErgonodeClient $ergonodeClient = null, ?stdClass $responseObject = null, public ErgonodeModel $parentModel)
    {
        parent::__construct($ergonodeClient, $responseObject);
    }
}
<?php


namespace Flooris\ErgonodeApi\Api\Exceptions;


use Exception;
use Throwable;

class ErgonodeConnectionError extends Exception
{
    public function __construct($message = "Could not connect with ergonode", $code = 503, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
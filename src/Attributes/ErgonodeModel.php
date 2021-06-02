<?php


namespace Flooris\ErgonodeApi\Attributes;


interface ErgonodeModel
{
    public function getErgonodeClient(): ErgonodeClient;
}

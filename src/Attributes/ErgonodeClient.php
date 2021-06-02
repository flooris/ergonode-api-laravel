<?php


namespace Flooris\ErgonodeApi\Attributes;


use Flooris\ErgonodeApi\ErgonodeApi;

interface ErgonodeClient
{
    public function getErgonodeApi(): ErgonodeApi;
    public function model(): ?ErgonodeModel;
}

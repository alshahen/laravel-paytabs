<?php


namespace Alshahen\Paytabs\Facades;


use Illuminate\Support\Facades\Facade;

class Paytabs extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'paytabs';
    }
}
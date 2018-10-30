<?php

namespace CarroPublic\MyInfo\Facades;

use Illuminate\Support\Facades\Facade;

class MyInfo extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'myinfo';
    }
}

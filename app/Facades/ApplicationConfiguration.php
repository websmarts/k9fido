<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ApplicationConfiguration extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'appconfig';
    }
}

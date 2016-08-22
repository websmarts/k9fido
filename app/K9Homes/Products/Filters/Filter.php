<?php

namespace App\K9homes\Products\Filters;

interface Filter
{
    public static function apply($query, $value);
}

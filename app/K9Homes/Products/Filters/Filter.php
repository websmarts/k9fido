<?php

namespace App\K9Homes\Products\Filters;

interface Filter
{
    public static function apply($query, $value);
}

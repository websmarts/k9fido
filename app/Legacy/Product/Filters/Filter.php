<?php

namespace App\Legacy\Product\Filters;

interface Filter
{
    public static function apply($query, $value);
}

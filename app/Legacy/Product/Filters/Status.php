<?php
namespace App\Legacy\Product\Filters;

class Status implements Filter
{
    public static function apply($query, $value)
    {
        $query->where('status', '=', $value);

        return $query;
    }
}

<?php
namespace App\K9Homes\Products\Filters;

class Status implements Filter
{
    public static function apply($query, $value)
    {
        $query->where('status', '=', $value);

        return $query;
    }
}

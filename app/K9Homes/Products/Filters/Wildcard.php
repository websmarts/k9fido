<?php
namespace App\K9homes\Products\Filters;

class Wildcard implements Filter
{
    public static function apply($query, $value)
    {
        $query->where('product_code', 'like', $value . '%');
        $query->orWhere('description', 'like', '%' . $value . '%');

        return $query;
    }
}

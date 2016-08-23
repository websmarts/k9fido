<?php

namespace App\Legacy\Product;

use App\Http\Controllers\FilterController as Filter;
use App\Legacy\Traits\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{

    use QueryFilter;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'k9homes';

    /**
     * The table primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'typeid';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'type';

    protected $fillable = [
        'name',
        'display_format',
        'type_description',
        'display_order',
    ];

    public $timestamps = false;

    /**
     * Scope a query to only include filtered results.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFiltered($query)
    {
        return $this->applyFilter($query, 'type');

    }

    public function options()
    {
        return $this->hasMany('App\Legacy\Product\ProductTypeOption', 'typeid', 'typeid');
    }

    public function products()
    {
        return $this->hasMany('App\Legacy\Product\Product', 'typeid', 'typeid');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Legacy\Category\Category', 'type_category', 'typeid', 'catid');
    }

}

<?php

namespace App\K9homes\Products;

//use App\Http\Controllers\FilterController as Filter;
use App\K9homes\Traits\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
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
    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    public $timestamps = false;

    protected $fillable = [
        'description',
        'size',
        'price',
        'product_code',
        'typeid',
        'qty_break',
        'qty_discount',
        'qty_instock',
        'qty_ordered',
        'special',
        'clearance',
        'can_backorder',
        'status',
        'cost',
        'last_costed_date',
        'supplier',
        'width',
        'height',
        'length',
        'shipping_volume',
        'shipping_weight',
        'shipping_container',
        'display_order',
        'barcode',
        'color_name',
        'color_background_color',
        'color_background_image',
        'notify_when_instock',
        'source',
        'new_product',
        'core_product',
        'low_stock_level',
        'rrp',
    ];

    /**
     * Scope a query to only include filtered results.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFiltered($query, $filters)
    {
        //return $this->applyFilter($query, 'product');
        //
        //
        // dd($filters->all());
        foreach ($filters->all() as $filterName => $value) {

            $decorator = $this->createFilterDecorator($filterName);

            if ($this->isValidDecorator($decorator)) {

                $query = $decorator::apply($query, $value);
            }

        }
    }

    public function createFilterDecorator($name)
    {
        return __NAMESPACE__ . '\\Filters\\' .
        str_replace(' ', '', ucwords(
            str_replace('_', ' ', $name)));
    }

    public function isValidDecorator($decorator)
    {
        // var_dump($decorator);
        return class_exists($decorator);
    }
}

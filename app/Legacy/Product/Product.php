<?php

namespace App\Legacy\Product;

use App\Legacy\Order\Item;
use App\Legacy\Traits\UsersQueryFilter;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use UsersQueryFilter;

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
        'actual_weight',
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
        'product_note',
    ];

    public function recentSales()
    {
        $sales = $this->sales()->get();

        // sum up the qty * price

        $total = $sales->reduce(function ($total, $item) {
            return $total + ($item->qty * $item->price);
        });

        return $total;
    }

    /**
     * Scope a query to only include filtered results.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApplyUserFilter($query)
    {
        return $this->applyFilter($query, $this->table);

    }

    public function bom()
    {
        return $this->hasMany(Bom::class, 'parent_product_code', 'product_code');
    }

    public function sales()
    {
        return $this->hasMany(Item::class,'product_code','product_code');
    }

    public function clientprices()
    {

        return $this->hasMany(ClientPrice::class, 'product_code', 'product_code');
        //->select(['product_code', 'client_id']);

    }

}

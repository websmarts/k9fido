<?php

namespace App\Legacy\Order;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

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
    protected $table = 'system_order_items';

    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'product_code',
        'product_id',
        'qty',
        'price',
        'qty_supplied',
        'supplied_product_id',

    ];

    public function product()
    {
        return $this->belongsTo('App\Legacy\Product\Product', 'product_code', 'product_code');
    }

    public function order()
    {
        return $this->belongsTo('App\Legacy\Order\Order', 'order_id', 'order_id');
    }

}

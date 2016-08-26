<?php

namespace App\Legacy\Order;

use App\Legacy\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasCompositePrimaryKey;

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
    protected $primaryKey = ['order_id', 'product_code'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_order_items';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_code',
        'qty',
        'price',
        'qty_supplied',

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

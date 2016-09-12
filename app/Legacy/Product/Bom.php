<?php

namespace App\Legacy\Product;

use Illuminate\Database\Eloquent\Model;

class Bom extends Model
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
    protected $table = 'boms';

    public $timestamps = false;

    protected $fillable = [
        'parent_product_code',
        'item_product_code',
        'item_price',
        'item_qty',
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'product_code', 'ite_product_code');
    }

}

<?php

namespace App\Legacy\Product;

use Illuminate\Database\Eloquent\Model;

class ClientPrice extends Model
{
    // use HasCompositePrimaryKey;

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
    protected $table = 'client_prices';

    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'product_code',
        'client_price',

    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'product_code', 'product_code')
            ->select(['product_code', 'price']);
    }

}

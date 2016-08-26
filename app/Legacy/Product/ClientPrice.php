<?php

namespace App\Legacy\Product;

use App\Legacy\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

class ClientPrice extends Model
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
    protected $table = 'client_prices';

    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'product_code',
        'client_price',

    ];

}

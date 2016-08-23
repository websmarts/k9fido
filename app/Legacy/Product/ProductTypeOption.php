<?php

namespace App\Legacy\Product;

use Illuminate\Database\Eloquent\Model;

class ProductTypeOption extends Model
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
    protected $table = 'type_options';

    protected $fillable = [
        'typeid',
        'opt_desc',
        'opt_class',
        'opt_code',
    ];

    public $timestamps = false;

}

<?php

namespace App\Legacy\Product;

use App\Legacy\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

class ProductTypeOption extends Model
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
    protected $primaryKey = ['typeid', 'opt_code'];

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

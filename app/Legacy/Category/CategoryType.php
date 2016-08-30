<?php

namespace App\Legacy\Category;

use App\Legacy\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

class CategoryType extends Model
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
    protected $primaryKey = ['catid', 'typeid'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'type_category';

    /**
     * @var mixed
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'catid',
        'typeid',

    ];

}

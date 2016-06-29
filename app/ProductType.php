<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
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
    protected $primaryKey = 'typeid';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'type';

    protected $fillable = [
        'name',
        'display_format',
        'type_description',
        'display_order',
    ];

    public $timestamps = false;

    public function options()
    {
        return $this->hasMany('App\ProductTypeOption', 'typeid', 'typeid');
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'typeid', 'typeid');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category', 'type_category', 'typeid', 'catid');
    }

}

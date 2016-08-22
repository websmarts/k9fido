<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
    protected $table = 'category';

    /**
     * @var mixed
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'display_order',

    ];

    /**
     * @var array
     */
    protected $casts = [
        'parent_id' => 'integer',
    ];

    /**
     * Scope a query to only return top level categories
     *
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTopLevel($query)
    {
        return $query->where('parent_id', '=', 0);
    }

    /**
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany('App\Category', 'parent_id', 'id');
    }

}

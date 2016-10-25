<?php

namespace App\Legacy\Order;

use App\Legacy\Traits\UsersQueryFilter;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use UsersQueryFilter;

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
    protected $table = 'system_orders';

    protected $dates = ['modified', 'created_at', 'updated_at'];

    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'status',
        'client_id',
        'instructions',
        'modified',
        'reference_id',
        'ordered_by',
        'longitude',
        'latitude',
        'order_contact',
        'exported',

    ];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApplyUserFilter($query)
    {
        return $this->applyFilter($query, $this->table);
    }

    public function client()
    {
        return $this->belongsTo('App\Legacy\Client\Client', 'client_id', 'client_id')
            ->select(['client_id', 'name', 'contacts', 'parent', 'address1', 'address2', 'address3', 'city', 'postcode', 'phone', 'custom_freight', 'freight_notes']);
    }

    public function items()
    {
        return $this->hasMany('App\Legacy\Order\Item', 'order_id', 'order_id');
    }

    public function salesrep()
    {
        return $this->belongsTo('App\Legacy\Staff\User', 'reference_id', 'id')
            ->select('id', 'firstname', 'lastname');
    }
}

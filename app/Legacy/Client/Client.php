<?php

namespace App\Legacy\Client;

use App\Legacy\Traits\UsersQueryFilter;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
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
    protected $primaryKey = 'client_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'status',
        'modified',
        'address1',
        'address2',
        'address3',
        'city',
        'postcode',
        'country',
        'phone_area_code',
        'phone',
        'phone2',
        'mobile',
        'fax',
        'contacts',
        'call_interval',
        'alert',
        'salesrep',
        'myob_card_id',
        'myob_record_id',
        'sales_rating',
        'client_type',
        'call_frequency',
        'call_planning_note',
        'login_user',
        'login_pass',
        'online_status',
        'online_validation_key',
        'online_contact',
        'level',
        'parent',
        'custom_freight',
        'freight_notes',

    ];

    /**
     * Scope a query to only include filtered results.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApplyUserFilter($query)
    {
        return $this->applyFilter($query, $this->table);

    }

    public function orders()
    {
        return $this->hasMany('App\Legacy\Order\Order', 'client_id');
    }

    public function parentGroup()
    {

        return $this->belongsTo('App\Legacy\Client\Client', 'parent', 'client_id');
    }

    public function prices()
    {
        return $this->hasMany('App\Legacy\Product\ClientPrice', 'client_id', 'client_id');
    }
}

<?php

namespace App\Legacy\Staff;

use Illuminate\Database\Eloquent\Model;

class UserClient extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'k9homes';

    public $timestamps = false;

    /**
     * The table used by the model.
     *
     * @var string
     */
    protected $table = 'user_clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'salesrep_id',
    ];

}

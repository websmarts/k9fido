<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = ['name', 'label'];

    public $timestamps = true;

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

}

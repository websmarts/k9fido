<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $table = 'roles';

    protected $fillable = ['title'];

    public $timestamps = false;

    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    // }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTypeFile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'typeid', 'filename', 'filepath','title','description', 'order'
    ];

    public $timestamps = true;
}

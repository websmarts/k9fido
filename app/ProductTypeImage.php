<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTypeImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'typeid', 'filename', 'height', 'width', 'order',
    ];

    protected $table = 'producttypeimages';

    public $timestamps = true;
}

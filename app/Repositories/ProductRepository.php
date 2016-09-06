<?php
namespace App\Repositories;

use App\Legacy\Product\Product;

class ProductRepository
{

    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

}

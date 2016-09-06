<?php
namespace App\Abstracts;

use App\Repositories\RepositoryInterface;

/**
 * This Abstract Repository provides default implementations of the methods defined
 * in the base repository interface. Thse simply delegate static function calls
 * to the right eloquent model based on the $modelClassName.
 */
abstract class Repository implements RepositoryInterface
{

    protected $modelClassName;

    public function create(array $attributes)
    {
        return call_user_func_array("{$this->modelClassName}::create", array($attributes));
    }

    public function all($columns = array('*'))
    {
        return call_user_func_array("{$this->modelClassName}::all", array($columns));
    }

    public function find($id, $columns = array('*'))
    {
        return call_user_func_array("{$this->modelClassName}::find", array($id, $columns));
    }

    public function destroy($ids)
    {
        return call_user_func_array("{$this->modelClassName}::destroy", array($ids));
    }

}

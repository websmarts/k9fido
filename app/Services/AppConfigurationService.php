<?php
namespace App\Services;

//K9config::get(key)

class AppConfigurationService
{

    protected $properties = [

        // product reference data
        'product.status.options' => ['active' => 'Active', 'inactive' => 'In-active', 'pending' => 'Pending', 'archive' => 'Archive'],
        'product.source.options' => ['B' => 'B', 'F' => 'F', 'M' => 'M'],
        'product.new_product.options' => ['0' => 'No', '1' => 'Yes'],
        'product.core_product.options' => ['0' => 'No', '1' => 'Yes'],
        'product.status.filter.options' => ['' => 'Any', 'active' => 'Active', 'inactive' => 'Inactive', 'pending' => 'Pending', 'archive' => 'Archive'],

        // product type ref data
        'type.status.options' => ['active' => 'Active', 'inactive' => 'In-active', 'pending' => 'Pending', 'archive' => 'Archive'],
        'type.status.filter.options' => ['' => 'Any', 'active' => 'Active', 'inactive' => 'Inactive', 'pending' => 'Pending', 'archive' => 'Archive'],
    ];

    public function get($property)
    {
        return isset($this->properties[$property]) ? $this->properties[$property] : null;
    }

    public function __get($property)
    {
        return isset($this->properties[$property]) ? $this->properties[$property] : null;
    }

}

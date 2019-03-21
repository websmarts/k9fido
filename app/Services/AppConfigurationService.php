<?php
namespace App\Services;

//Appdata::get(key)

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

        // Order ref data
        'order.status.options' => [
            'basket' => 'Basket',
            'saved' => 'Saved (new)',
            'printed' => 'To be picked',
            'parked' => 'Parked',
            'picked' => 'Picked'],

        //Order exported options
        'order.exported.options' => [
            'no' => 'No',
            'yes' => 'Yes',
        ],

        //Sales  options
        'sales.report.period.options' => [
            null => 'None',
            -1 =>'All',
            180 => '180 days',
            120 => '120 days',
            90 => '90 days',
            60 => '60 days',
            30 => '30 days'
        ],
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

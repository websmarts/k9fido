<?php
namespace App\Repositories;

/**
 * This OrderRepositoryInterface contains ONLY method signatures
 * related to the Order Object
 */
interface OrderRepositoryInterface
{

    public function index($paginate);

    public function get($id);

    public function totalItemsCost($order);

    public function findOrdersToPick();

    public function clientPrices($client);

    public function exportOrder($order);
}

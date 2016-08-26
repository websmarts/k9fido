<?php
namespace App\Queries;

use App\Legacy\Order\Order;

class OrderListQuery
{
    public static function perform()
    {
        return (new static )->handle();
    }
    public function handle()
    {

        $query = Order::with('client')
            ->applyUserFilter()
            ->join('clients', 'system_orders.client_id', '=', 'clients.client_id')
            ->select('clients.name', 'system_orders.*')
            ->orderBy('system_orders.id', 'desc');
        // dd($query->toSql());

        $paginate = 10;

        return $query->paginate($paginate);

    }

}

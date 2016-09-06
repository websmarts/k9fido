<?php
namespace App\Repositories;

use App\Legacy\Order\Order;
use App\Legacy\Product\ClientPrice;
use App\Legacy\Traits\CanExportOrder;

class OrderRepository
{
    use CanExportOrder;

    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function index($paginate = 10)
    {
        return $this->model->with('client')
            ->applyUserFilter()
            ->join('clients', 'system_orders.client_id', '=', 'clients.client_id')
            ->select('clients.name', 'system_orders.*')
            ->orderBy('system_orders.id', 'desc')
            ->paginate($paginate);
    }

    public function get($id)
    {
        return $this->model->with('items', 'client')->find($id);

    }

    public function totalItemsCost($order)
    {

        if (!$order instanceof Order) {
            $order = $this->model->find($order);
        }

        return $order->items->sum(function ($item) {
            return $item['qty'] * ($item['price'] / 100);
        });
    }

    /**
     * find any orders waiting to be picked
     * @method findOrdersToPick
     * @return [type]           [description]
     */
    public function findOrdersToPick()
    {

        return $this->model->where('status', 'printed')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function clientPrices($clientId)
    {
        return collect(ClientPrice::where('client_id', $clientId)
                ->select('product_code', 'client_price')
                ->get()
        )->keyBy('product_code');
    }

    public function exportOrder($id)
    {
        return $this->export($this->get($id));
    }
}

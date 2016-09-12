<?php
namespace App\Services;

use App\Legacy\Order\Item;
use App\Legacy\Order\Order;
use App\Legacy\Product\ClientPrice;
use App\Legacy\Product\Product;
use App\Legacy\Traits\CanExportOrder;

class OrderService
{
    use CanExportOrder;

    protected $order;

    /*
     * List of orders in repo instance
     */
    protected $orders = [];

    public function __construct(Order $model)
    {
        $this->order = $model;
    }

    public function index($paginate = 10)
    {
        return $this->order->with('client')
            ->applyUserFilter()
            ->join('clients', 'system_orders.client_id', '=', 'clients.client_id')
            ->select('clients.name', 'system_orders.*')
            ->orderBy('system_orders.id', 'desc')
            ->paginate($paginate);
    }

    public function getItem($id = false)
    {
        if (!$id) {
            return new Item();
        }
        if (is_array($id)) {
            return $this->getItemsByAttributes($id)->first();
        }
        return $this->order->find($id);
    }
    public function getItemsByAttributes($attributes)
    {
        $query = new Item();
        foreach ($attributes as $attr => $value) {
            $query->where($attr, $value);
        }
        return $query->get();
    }

    public function getOrderById($id)
    {
        return $this->order->find($id);
    }

    public function totalItemsCost($order)
    {

        if (!$order instanceof Order) {
            $order = $this->order->find($order);
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

        return $this->order->where('status', 'printed')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function clientPrices($clientId, $productCode = false)
    {
        $query = ClientPrice::where('client_id', $clientId);
        if ($productCode) {
            $query->where('product_code', $productCode);
        }
        return collect($query->select('product_code', 'client_price')
                ->get()
        )->keyBy('product_code');
    }

    public function exportOrder($id)
    {
        return $this->export($this->getOrderById($id));
    }

    public function deleteOrderItems($items)
    {
        foreach ($items as $item) {
            $this->deleteOrderItem($item);
        }
    }
    public function deleteOrderItem($item)
    {
        $orderItem = $this->getOrderItem($item);

        $orderItem->product->qty_instock += ($orderItem->qty);
        $orderItem->product->save();
        $orderItem->delete();
    }

    protected function getOrderItem($item)
    {
        return $item->with(['product', 'order.client'])
            ->where('product_code', $item->product_code)
            ->where('order_id', $item->order_id)
            ->first();
    }

    public function addOrderItems($items)
    {
        foreach ($items as $item) {
            $this->addOrderItem($item);
        }
    }
    public function addOrderItem($item)
    {
        if ($this->validProductCode($item->product_code)) {
            $item->price = $this->getProductPrice($item);
            $item->save();

            $this->updateStockQuantity($item->product, -$item->qty);
        }
    }

    public function updateOrderItems($items)
    {
        foreach ($items as $item) {
            $this->updateOrderItem($item);
        }
    }

    public function updateOrderItem($item)
    {
        // get the current orderItem from DB;
        $orderItem = $this->getOrderItem($item);

        if (!is_null($orderItem)) {
            $originalItemQty = $orderItem->qty;
            $orderItem->qty = $item->qty;
            // if price empty get system price

            if ($item->price == '') {
                $orderItem->price = $this->getProductPrice($item);
            } else {
                $orderItem->price = $item->price;
            }
            $orderItem->save();

            $stockAdjustQuantity = $originalItemQty - $item->qty;

            $this->updateStockQuantity($orderItem->product, $stockAdjustQuantity);

        }

    }

    protected function updateStockQuantity($product, $adjust)
    {
        //dd(['product' => $product->bom, 'adjust' => $adjust]);
        // if product is a BOM then restock each BOM item_qty times the adjustment
        if ($product->bom->count()) {
            foreach ($bom as $bomItem) {
                $this->updateStockQuantity($bomItem->product, $bomItem->qty * $adjust);
            }
        } else {
            $product->qty_instock += $adjust;
            //dd($product);
            $product->save();
        }

    }

    protected function validProductCode($productCode)
    {
        return $this->getProductByProductCode($productCode);
    }

    /**
     * [getProductByProductCode description]
     * @method getProductByProductCode
     * @param  [string]                  $productCode [product_code]
     * @return [array]                               [product item]
     */
    protected function getProductByProductCode($productCode)
    {
        return Product::where('product_code', $productCode)->first();
    }

    protected function getProductPrice($item)
    {
        if (is_null($product = $this->getProductByProductCode($item->product_code))) {
            return null;
        }

        //dd($item->order);
        //dd($product->price);
        $item->order->client->load('prices');
        if ($clientPrice = $item->order->client->prices()->where('product_code', $item->product_code)->first()) {

            // dd($clientPrice);

            return $clientPrice->client_price;

        }
        // dd($product->price);
        return $product->price;
    }

    public function updateOrderStatus($orderId, $status = false)
    {
        if ($status && in_array($status, array_keys(\Appdata::get('order.status.options')))) {
            return Order::find($orderId)->update(['status' => $status]);
        }
    }

    public function deleteOrder($orderId, $restock = true)
    {
        $order = Order::with('items.product.bom')->find($orderId);

        foreach ($order->items as $item) {
            if ($restock) {
                $this->updateStockQuantity($item->product, $item->qty);
            }
            $item->delete();
        }
        $order->delete();
    }
}

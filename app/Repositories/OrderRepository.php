<?php
namespace App\Repositories;

use App\Legacy\Order\Order;
use App\Legacy\Product\ClientPrice;
use App\Legacy\Product\Product;
use App\Legacy\Traits\CanExportOrder;

class OrderRepository
{
    use CanExportOrder;

    protected $model;

    /*
     * List of orders in repo instance
     */
    protected $orders = [];

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
        return $this->export($this->get($id));
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

    public function addOrderItem($item)
    {
        if ($this->validProductCode($item->product_code)) {
            $item->price = $this->getProductPrice($item);
            $item->save();

            $this->updateStockQuantity($item, -$item->qty);
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
        //$orderItem = $this->getOrderItem($item);
        $orderItem = $item
            ->where('product_code', $item->product_code)
            ->where('order_id', $item->order_id)
            ->first();

        dd($orderItem->order->client->prices);

        if (!is_null($orderItem)) {
            $originalItemQty = $orderItem->qty;
            $orderItem->qty = $item->qty;
            // if price empty get system price
            if (empty($item->price)) {
                $orderItem->price = $this->getProductPrice($item);
            } else {
                $orderItem->price = $item->price;
            }
            $orderItem->save();

            $stockAdjustQuantity = $originalItemQty - $item->qty;
            $this->updateStockQuantity($orderItem, $stockAdjustQuantity);

        }

    }

    /**
     * [updateStatusAndItems description]
     * @method updateStatusAndItems
     *
     * @param  [collection]               $items   [description]
     * @return [void]                        [description]
     */
    // public function was_updateOrderItems($items)
    // {
    //     dd($items);
    //     $order = $this->model->with('client', 'items')->find($orderId);

    //     if (!is_null($order) && is_array($items) && count($items) > 0) {

    //         foreach ($items as $itemkey => $itemData) {
    //             $orderItem = OrderItem::find($itemkey);

    //             if (is_null($orderItem)) {
    //                 // must be a new item so add it
    //                 $itemData['order_id'] = 'T0_' . $orderId;

    //                 if (empty($itemData['price'])) {
    //                     $itemData['price'] = $this->getProductPrice($itemData['product_code'], $order->client);
    //                 }

    //                 if ($this->validProductCode($itemData['product_code'])) {
    //                     // If stock or can_backorder set
    //                     OrderItem::create($itemData);
    //                     $this->updateStockQuantity($itemData['product_code'], -$itemData['qty']);
    //                 }

    //             } else {
    //                 // update existing item
    //                 if ($itemData['qty'] < 0) {
    //                     $this->updateStockQuantity($itemData['product_code'], $orderItem->qty);
    //                     $orderItem->delete();
    //                 } else {
    //                     $this->updateStockQuantity($itemData['product_code'], $orderItem->qty - $itemData['qty']);
    //                     $orderItem->update($itemData);
    //                 }
    //             }
    //         }
    //     }
    // }

    protected function updateStockQuantity($item, $adjust)
    {

        // if product is a BOM then restock each BOM item_qty times the adjustment
        if ($bom = $item->product->bom) {
            foreach ($bom as $bomItem) {
                $this->updateStockQuantity($bomItem, $bomItem->qty * $adjust);
            }
        } else {
            $item->product->qty_instock += $adjust;
            $item->product->qty_ordered += $adjust;
            $item->product->save();
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

        //dd($client);
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
        if (!$status || !in_array($status, array_keys(\Appdata::get('order.status.options')))) {
            return;
        }
        return Order::find($orderId)->update(['status' => $status]);
    }

    public function deleteOrder($orderId, $restock = true)
    {
        $order = Order::with('items.product.bom')->find($orderId);

        dd($order);

        foreach ($order->items as $item) {
            $bom = $this->isBom($item->product->product_code);
            if ($bom) {
                // return each BOM items qty to stock
            } else {
                $item->product->qty_instock += $item->qty;
            }

            $item->product->save();
            $item->delete();
        }
    }

    // public function updateItems($id, $items = [])
    // {
    //     $order = $this->model->with('items')->find($id);

    //     foreach ($order->items as $item) {
    //         if (!isset($items[$item->id])) {
    //             // Delete the item as it is no longer in the list
    //             continue;
    //         }

    //         foreach (array_keys($items[$item->id]) as $key) {
    //             $item->$key = $items[$item->id][$key];
    //         }
    //         $item->update();

    //     }

    // }

    /**
     * Add a new item to an order
     * The item can have a qty, product_code and a price
     * If price is empty the default pricing is used
     * If price is not empty then the price entered is used
     * If the price entered is a discount then it is applied to
     * the normal price the client would be charged
     *
     * Note Product code needs to exist, item will not be added
     * if the product code is not valid.
     *
     * @method addItem
     * @param  [int]  $id   [order id]
     * @param  [array]  $item [qty,product_code,price]
     */
    // public function addItem($orderId, $item = [])
    // {

    //     if (!$this->validItem($item)) {
    //         dd($item);
    //         return;
    //     }

    //     // Get product details - why - verify product code exists and get std price

    //     if (!$product = $this->getProductByProductCode($item['product_code'])) {
    //         dd($product);
    //         return;
    //     }

    //     // Check if clent has a special price for this product

    //     if (!$order = $this->model->with('client.prices')->find($orderId)) {
    //         dd($order);
    //         return;
    //     }

    //     // Check for a special client price

    //     $special = $order->client->prices->first(function ($key, $value) use ($product) {
    //         return $value->product_code = $product->product_code;
    //     });

    //     if ($special) {
    //         $productPrice = $special->client_price;
    //     } else {
    //         $productPrice = $product->price;
    //     }

    //     // Check if we are applying a discount

    //     // if item.price is a % then apply it to std price
    //     $price = trim($item['price']);

    //     if ($pos = strpos($price, '%') !== false) {
    //         $discount = ((int) substr($price, 0, $pos + 1) / 100);
    //         //dd($discount);
    //         $productPrice = (int) $productPrice * (1 - $discount);
    //     }

    //     $data = [
    //         'qty' => (int) $item['qty'],
    //         'product_code' => $product->product_code,
    //         'product_id' => $product->id,
    //         'price' => $productPrice,

    //     ];

    //     dd($data);

    //     $order = $this->model->find($orderId);

    //     if (!is_null($order)) {
    //         $order->items()->save($data);
    //     }

    // }

}

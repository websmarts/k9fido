<?php
namespace App\Services;

use App\Legacy\Order\Item;
use App\Legacy\Order\Order;
use App\Legacy\Product\ClientPrice;
use App\Legacy\Product\Product;
use App\Legacy\Traits\CanExportOrder2;

class OrderService
{
    use CanExportOrder2;

    protected $order;

    /*
     * List of orders in repo instance
     */
    protected $orders = [];

    /*
     * Extra descriptive lines added to myob export
     * using Product codes /FRST and /FRDL
     */
    protected $frst = ''; // Delivery comment line added to exported order
    protected $frdl = ''; // Freight comment line in exported order

    public function __construct(Order $model)
    {
        $this->order = $model;

        // Get the extra comment lines Darren wants to put into orders when they are exported to MYOB
        if($productFrst = Product::where('product_code','/FRST')->first()) {
            $this->frst = $productFrst->description; // Note maybe convert emebdded charaters to new lines here??
        }       

        if($productFrdl = Product::where('product_code','/FRDL')->first()) {
             $this->frdl = $productFrdl->description; // Note maybe convert emebdded charaters to new lines here??
        }
           
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

    /**
     * get and format data for displaying an order view
     * @method fetchOrderDisplayData
     * @param  [type]                $orderId [description]
     * @return [type]                         [description]
     */
    public function fetchOrderDisplayData($orderId)
    {

        $order = $this->getOrderById($orderId);
        $orderItems = $this->getOrderItemsWithProduct($orderId);
        $clientprices = $this->clientPrices($order->client->client_id);
        $freight = $this->newGetFreightInformation($order->client);
        $oldfreight = $this->getFreightInformation($order->client);
        $totalItemsCost = 0;
        $totalItemsPrice = 0;
        $items = [];

        foreach ($orderItems as $item) {
            $strategy = '';
            $customDiscount = null;
            $i = new \stdClass();
            //dd($item);
            if (isset($clientprices[$item->product_code])) {

                if ($clientprices[$item->product_code]->client_price == $item->price) {
                    $strategy = '#s'; //'special_client_price';
                    // $customDiscount = $item->product->price > 0 ? number_format(100 * ($item->product->price - $item->price) / $item->product->price, 1) : '';
                }
            } else if (($item->product->qty_break > 0)
                && ($item->qty >= $item->product->qty_break)
                && ($item->price == round(($item->product->price * (1 - ($item->product->qty_discount / 100)))))
            ) {

                $strategy = '#q'; //'quantity_discount';

            } else if ($item->price != $item->product->price) {

                $strategy = '#c'; //'custom_pricing';
                // $customDiscount = $item->product->price > 0 ? number_format(100 * ($item->product->price - $item->price) / $item->product->price, 1) : '';

            }

            $customDiscount = $item->product->price > 0 ? round(100 * ($item->product->price - $item->price) / $item->product->price, 3) : '';// 
            // Markup
            $markup = '';
            if ($item->price > 0) {
                $markup = ($item->price - $item->product->cost) / $item->price;
            }
            $i->qty = $item->qty;
            $i->qty_supplied = $item->qty_supplied;
            $i->product_code = $item->product_code;
            $i->description = $item->description;
            $i->price = $item->price;
            $i->product = $item->product;
            $i->pricing_strategy = $strategy;
            $i->ext_price = $item->qty * $item->price; // NOTE - MAy need to convert to 2 dec places at some pont to keep MYOB happy??
            $i->markup = $markup;
            $i->custom_discount = $customDiscount;
            //dd($i);
            $items[] = $i;

            $totalItemsCost += $item->qty * $item->product->cost;
            $totalItemsPrice += $i->ext_price;
        }
        // dump($items);
        return (compact('order', 'freight', 'oldfreight', 'items', 'totalItemsCost', 'totalItemsPrice'));
    }
    protected function newGetFreightInformation($client)
    {

        $freight = FreightService::create()->getFreightRates($client->postcode,$client->city);

        if ($client->custom_freight > 0) {
            $freight['notes'] = $client->freight_notes;
        }
        return $freight;
    }
    protected function getFreightInformation($client)
    {
        $result = new \stdClass();
        if ($client->custom_freight > 0) {
            $result->code = 'Custom freight';
            $result->notes = $client->freight_notes;
            return $result;
        }

        $freight = FreightService::create()->getFreightCode($client->postcode);
        $result->code = $freight[1] == 'local' ? $freight[0] . ' Local' : $freight[0];
        return $result;
    }

    protected function itemDisplayData($orderId)
    { }

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
            return $item['qty'] * ($item['price'] / 100);// NOTE MAY NEED TO ROUND TO 2 DEC PLACES AS PER DARRENS EMAIL
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
        return collect(
            $query->select('product_code', 'client_price')
                ->get()
        )->keyBy('product_code');
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
            if (!$this->itemExists($item)) {
                $item->price = $this->getProductPrice($item);
                $item->save();
                $this->updateStockQuantity($item->product, -$item->qty);
            }
        }
    }

    protected function itemExists($item)
    {
        //dd($item);
        return Item::where('order_id', $item->order_id)->where('product_code', $item->product_code)->first();
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

            $orderItem->qty_supplied = $item->qty_supplied;

            $orderItem->save();

            $stockAdjustQuantity = $originalItemQty - $item->qty;

            $this->updateStockQuantity($orderItem->product, $stockAdjustQuantity);
        }
    }

    protected function updateStockQuantity($product, $adjust)
    {
        if (!$product) {
            return;
        }

        //dd(['product' => $product->bom, 'adjust' => $adjust]);
        // if product is a BOM then restock each BOM item_qty times the adjustment
        if ($product->bom->count()) {
            // dd($product->bom->count());
            foreach ($product->bom as $bomItem) {
                //dd($bomItem);
                $bomItemProduct = Product::where('product_code', $bomItem->item_product_code)->first();
                //dd($bomItemProduct);
                $this->updateStockQuantity($bomItemProduct, $bomItem->item_qty * $adjust);
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

    public function updateOrderExportedStatus($orderId, $exported = false)
    {
        if ($exported && in_array($exported, array_keys(\Appdata::get('order.exported.options')))) {
            return Order::find($orderId)->update(['exported' => $exported]);
        }
    }

    public function updateOrderFreightCharge($orderId, $freight = 0)
    {
        $freight = (float)$freight;
        return Order::find($orderId)->update(['freight_charge' => $freight]);
    }

    public function deleteOrder($orderId, $restock = true)
    {
        $order = Order::with('items.product.bom')->find($orderId);

        foreach ($order->items as $item) {
            if ($restock && $order->status !== 'basket') {
                $this->updateStockQuantity($item->product, $item->qty);
            }
            $item->delete();
        }
        $order->delete();
    }

    /**
     * Updates qty_supplied for an ordered item
     * @method  updateItemQuantitySupplied
     * @param  [int]            $itemId [system_order_item id]
     * @param  [int]            $qty [qty supplied]
     * @return [type]                   [description]
     */
    public function updateItemQuantitySupplied($itemId, $qty)
    {
        $item = Item::find($itemId);
        $item->qty_supplied = $qty;
        $item->save();
    }

    public function getOrderItemsWithProduct($orderId)
    {
        return Item::with('product')->where('order_id', 'T0_' . $orderId)->orderBy('product_code', 'asc')->get();
    }
}

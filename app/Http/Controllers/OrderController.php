<?php

namespace App\Http\Controllers;

use App\Legacy\Order\Item;
use App\Legacy\Order\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService; // the Order Service

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth');
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of Orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = $this->orderService->index(10);
        $filterKey = "system_orders"; // must match a key in FilterController
        return view('admin.order.index', compact('orders', 'filterKey'));
    }

    /**
     * Display the Order detail.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get the order and order items
        $order = $this->orderService->getOrderById($id);
        $clientprices = $this->orderService->clientPrices($order->client->client_id);
        $totalItemsCost = $this->orderService->totalItemsCost($order);
        return view('admin.order.show', compact('order', 'clientprices', 'totalItemsCost'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = $this->orderService->getOrderById($id);
        $clientprices = $this->orderService->clientPrices($order->client->client_id);
        $totalItemsCost = $this->orderService->totalItemsCost($order);
        return view('admin.order.edit', compact('order', 'clientprices', 'totalItemsCost'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $orderId)
    {

        $items = $this->sortInputItems($request, $orderId);

        $this->orderService->updateOrderItems($items['update']);
        $this->orderService->deleteOrderItems($items['delete']);
        $this->orderService->addOrderItems($items['new']);
        $this->orderService->updateOrderStatus($orderId, $request->input('status'));

        flash('Order updated', 'success');
        return redirect(route('order.edit', ['id' => $orderId]));
    }

    /**
     * Remove the Order and all all ordered Items
     *
     * Put Ordered Items back into stock
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete the order
        $this->orderService->deleteOrder($id);
        flash('Order ' . $id . 'has been deleted', 'success');
        return redirect(route('order.index'));
    }

    public function export($id)
    {
        return $this->orderService->exportOrder($id);
    }

    public function pick($id)
    {
        $order = Order::with('client')->find($id);

        return view('admin.order.pick', compact('order'));
    }
    /**
     * ajax handler for pick order app
     * @method pickorder
     * @return [type]    [description]
     */
    public function pickorderGet($id)
    {
        $order = Order::with('items.product')->find($id);
        $data = [];
        foreach ($order->items as $i) {
            if ($i->qty_supplied >= $i->qty) {
                continue;
            }
            $x = new \stdClass();
            $x->barcode = $i->product->barcode;
            $x->product_code = $i->product_code;
            $x->id = $i->id;
            $x->qty = $i->qty;
            $x->qty_supplied = $i->qty_supplied;

            $data[] = $x;
        }
        return $data;
    }
    public function pickorderStore(Request $request)
    {
        $items = $request->input('items');
        $orderId = $request->input('order_id');
        $status = $request->input('status');

        //return $items;
        if (is_array($items) && count($items) > 0) {
            foreach ($items as $item) {
                $this->orderService->updateItemQuantitySupplied($item['id'], $item['qty_supplied']);
            }
        }

        $this->orderService->updateOrderStatus($orderId, $status);

        return ['result' => 200, 'location' => route('home')];
    }

    /**
     * Sorts the input items from the edit order form
     * @method sortInputItems
     * @param  [Request]     $request [HTTP request]
     * @param  [int]         $orderId [the order ID]
     * @return [array of collections]  [items by intent, update, delete or new]
     */
    protected function sortInputItems($request, $orderId)
    {
        $updateItems = collect([]);
        $deleteItems = collect([]);
        $addItems = collect([]);
        foreach ($request->input('items') as $itemId => $i) {
            $item = $this->orderService->getItem();
            $item->qty = $i['qty'];
            $item->qty_supplied = $i['qty_supplied'];
            $item->product_code = $i['product_code'];
            $item->price = trim($i['price']);
            $item->order_id = 'T0_' . $orderId;
            if ($itemId > 0) {
                // its an existing item
                $item->id = $itemId;
                if ($item->qty < 0) {
                    $deleteItems->push($item);
                } else {
                    $updateItems->push($item);
                }
            } elseif ($itemId < 0) {
                // it is a new item being added to order
                unset($item->id); //just to be sure we have no id set
                $addItems->push($item);
            }
        }
        return ['update' => $updateItems, 'delete' => $deleteItems, 'new' => $addItems];
    }

}

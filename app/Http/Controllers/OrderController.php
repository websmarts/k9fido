<?php
namespace App\Http\Controllers;

use App\Legacy\Order\Item;
use App\Legacy\Order\Order;
use App\Legacy\Product\Product;
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
        // $data = $this->orderService->fetchOrderDisplayData($id);
        // dd($data);
        return view('admin.order.show', $this->orderService->fetchOrderDisplayData($id));
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

        // dd($items);

        $this->orderService->updateOrderItems($items['update']);
        $this->orderService->deleteOrderItems($items['delete']);
        $this->orderService->addOrderItems($items['new']);
        $this->orderService->updateOrderStatus($orderId, $request->input('status'));
        $this->orderService->updateOrderExportedStatus($orderId, $request->input('exported'));
        $this->orderService->updateOrderFreightCharge($orderId, $request->input('freight_charge'));

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

        // Sort order Items

        $sorted = $order->items->sortBy('product_code');

        $result['data'] = [];
        foreach ($sorted as $i) {
            if ($i->qty_supplied >= $i->qty) {
                continue;
            }
            $x = new \stdClass();
            $x->barcode = (float) $i->product->barcode;
            $x->product_code = $i->product_code;
            $x->description = $i->product->description;
            $x->id = $i->id;
            $x->qty = $i->qty;
            $x->qty_supplied = $i->qty_supplied;
            $x->product_note = $i->product->product_note;

            $result['data'][] = $x;
        }

        // Sort $result['data'] array by product code

        $result['status'] = 'success';
        return $result;
    }
    public function pickorderStore(Request $request)
    {
        $items = $request->input('items');
        $orderId = $request->input('order_id');
        $status = $request->input('status');

        // return $items;

        if (is_array($items) && count($items) > 0) {
            foreach ($items as $item) {
                if (isset($item['picked_qty'])) {
                    $this->orderService->updateItemQuantitySupplied($item['id'], $item['picked_qty']);
                }
            }
        }

        $this->orderService->updateOrderStatus($orderId, $status);

        return ['status' => 'success', 'location' => route('home')];
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
            $item->product_code = $this->getRealProductCode($i['product_code']);

            // If product code is not valid then skip
            if (!$item->product_code) {
                //continue;
            }
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
            } elseif ($itemId < 0 && $item->qty > 0 && $this->getRealProductCode($item->product_code)) {
                // it is a new item being added to order, id = -1 for new item
                unset($item->id); //just to be sure we have no id set
                $addItems->push($item);
            }
        }
        return ['update' => $updateItems, 'delete' => $deleteItems, 'new' => $addItems];
    }

    protected function getRealProductCode($productCode)
    {
        $product = $this->getProductByProductCode($productCode);
        if ($product) {
            return $product->product_code;
        }
        return false;
    }

    protected function getProductByProductCode($productCode)
    {
        return Product::where(function ($q) use ($productCode) {
            $q->whereRaw(' LOWER(`product_code`) = ?', [strtolower($productCode)]);
        })->first();

    }

}

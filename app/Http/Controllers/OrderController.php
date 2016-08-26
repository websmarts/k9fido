<?php

namespace App\Http\Controllers;

use App\Legacy\Order\Item;
use App\Legacy\Order\Order;
use App\Legacy\Product\ClientPrice;
use App\Queries\OrderListQuery;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $orders = OrderListQuery::perform();

        return view('admin.order.index')->with('orders', $orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get the order and order items
        $order = Order::with('items', 'client')->find($id);
        $clientprices = collect(ClientPrice::where('client_id', $order->client_id)
                ->select('product_code', 'client_price')
                ->get()
        )->keyBy('product_code');

        return view('admin.order.show')->with(['order' => $order, 'clientprices' => $clientprices]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        // Get the order and order items
        $order = Order::with('items', 'client')->find($id);
        $clientprices = collect(ClientPrice::where('client_id', $order->client_id)
                ->select('product_code', 'client_price')
                ->get()
        )->keyBy('product_code');

        return view('admin.order.edit')->with(['order' => $order, 'clientprices' => $clientprices]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Check status to see if order can be edited (not exporteded)

        // Update the order details
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete the order
        // Delete the order items
        // Return order items to stock

    }

    public function export($id)
    {
        $order = Order::with('items', 'client')->find($id);
        return $order->export;
    }

    public function editOrderItem($orderId, $productCode)
    {
        $item = Item::where('order_id', $orderId)
            ->where('product_code', $productCode)
            ->first();
        // return $item->order->client_id . '---' . $productCode;
        $clientPrice = ClientPrice::where('product_code', $productCode)
            ->where('client_id', $item->order->client_id)
            ->select('client_price as price')
            ->first();

        return view('admin.order.edititem')->with(['item' => $item, 'clientPrice' => $clientPrice]);
    }

}

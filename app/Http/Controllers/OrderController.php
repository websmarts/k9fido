<?php

namespace App\Http\Controllers;

use App\Legacy\Order\Item;
use App\Legacy\Order\Order;
use App\Legacy\Product\ClientPrice;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $repo; // the Order Repository

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->middleware('auth');

        $this->repo = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $orders = $this->repo->index(10);

        $filterKey = "system_orders"; // must match a key in FilterController

        return view('admin.order.index', compact('orders', 'filterKey'));
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
        $order = $this->repo->get($id);

        $clientprices = $this->repo->clientPrices($order->client->client_id);

        $totalItemsCost = $this->repo->totalItemsCost($order);

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

        $order = $this->repo->get($id);

        $clientprices = $this->repo->clientPrices($order->client->client_id);

        $totalItemsCost = $this->repo->totalItemsCost($order);

        return view('admin.order.edit', compact('order', 'clientprices', 'totalItemsCost'));
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
        $order = Order::with('items.product')->find($id);

        //dd($order->items);
        foreach ($order->items as $item) {
            $item->product->qty_instock += ($item->qty - $item->qty_supplied);
            $item->product->save();
            $item->delete();
        }
        // Delete the order items
        $order->delete();

        // Return order items to stock
        flash('Order ' . $id . 'has been deleted', 'success');
        return redirect(route('order.index'));

    }

    public function export($id)
    {
        return $this->repo->exportOrder($id);
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

    public function pick($id)
    {

        $order = Order::with('items.product', 'client')->find($id);

        return view('admin.order.pick', compact('order'));
    }

}

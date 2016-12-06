<?php

namespace App\Http\Controllers;

use App\Legacy\Order\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class ExportController extends Controller
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
     * Client details exports. Export a csv file with the order line-items
     */
    public function detail($id)
    {
        $order = Order::with('items.product')->find($id);
        $header = 'description,size,price,product_code,barcode,color_name' . "\r\n";
        $o = '';
        if ($order->items->count()) {
            foreach ($order->items as $item) {
                $o .= $this->cleanString($item->product->description) . ',' .
                $item->product->size . ',' .
                number_format($item->price / 100, 2) . ',' .
                $item->product_code . ',' .
                $item->product->barcode . ',' .
                $item->product->color_name . "\r\n";

            }
        }
        //dd($o);
        // output as csv file.
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="' . $order->order_id . '_items.txt"');
        echo $o;
        exit;

    }
    protected function cleanString($str)
    {

        // quote commas in csv
        $str = trim($str); //remove any newlines
        $str = strip_tags($str);

        // return str_replace(',','","',$str); // changed 21/09/2015 because commas in addresses were screwing things up on export.
        return str_replace(',', ';', $str); // replace comms with semi colons

    }

    /**
     * MYOB Exports below
     */

    /**
     * [export description]
     * @method export
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function export($id)
    {
        session(['exportorders' => $id]);
        return view('admin.order.export', compact('id'));

    }

    public function batchexport(Request $request)
    {
        //dd($request->input('exportorders'));
        $id = $request->input('exportorders');
        session(['exportorders' => $id]);
        return view('admin.order.export', compact('id'));
    }
    public function download(Request $request)
    {

        $order = session('exportorders');
        //dd($order);
        if ($order) {
            return $this->orderService->export($order);
        }
        return view('admin.order.noexports');
    }
}

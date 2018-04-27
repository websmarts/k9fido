<?php

namespace App\Http\Controllers;

use App\Legacy\Order\Item;
use App\Legacy\Product\Product;

class ProductReportsController extends Controller
{

    public function index()
    {
        $products = Product::select('product_code')
            ->where('status', 'active')
            ->get();

        $results = [];

        // get the items ordered for each product in the last year
        foreach ($products as $p) {
            $items = Item::with('order')->where('product_code', $p->product_code)->get();
            // dd($items->first()->toArray());
            foreach ($items as $item) {
                if ($item->order && $item->order->client_id) {
                    $results[$p->product_code][$item->order->client_id][] = $item;
                }
            }
        }

        // Group sales amount by client

        return view('admin.reports.product_sales', compact('results'));
    }

}

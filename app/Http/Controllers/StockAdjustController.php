<?php

namespace App\Http\Controllers;

use App\Legacy\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustController extends Controller
{
    public function index()
    {
        return view('admin.stockadjust');
    }

    public function find(Request $request)
    {
        // Note code maybe a barcode OR a product_code
        // If only numbers then it is likely a barcode
        // and if contains alpha character it is likely a product code

        if (ctype_digit($request->barcode)) {
            // treat as barcode if it only has digits
            $product = Product::where('barcode', $request->barcode)->first();
        } else {
            $product = Product::where('product_code', $request->product_code)->first();
        }

        $product->ordered = $this->getOrderedQty($product->product_code);

        if ($product) {
            return response()->json($product, 200);
        }
        return response()->json(['message' => 'No matching product found'], 404);

    }
    public function update(Request $request, $product)
    {
        if ($product = Product::find($product)) {
            $product->qty_instock = (int) $request->qty_instock;
            $product->save();

            $product->ordered = $this->getOrderedQty($product->product_code);

            return response()->json($product, 200);
        }
        return response()->json(['message' => 'No matching product found'], 404);
    }

    protected function getOrderedQty($productCode)
    {
        $sql = 'SELECT SUM(SOI.qty) AS ordered FROM
                system_orders SO
                LEFT JOIN system_order_items SOI ON SO.order_id = SOI.order_id
                WHERE SO.status IN ("saved","parked","printed")
                AND SOI.product_code = ? ';

        $onOrderQty = DB::connection('k9homes')->select($sql, [$productCode]);

        return (int) $onOrderQty[0]->ordered;
    }
}

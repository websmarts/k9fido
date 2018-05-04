<?php

namespace App\Http\Controllers;

use App\Legacy\Product\Product;
use Illuminate\Http\Request;

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

            return response()->json($product, 200);
        }
        return response()->json(['message' => 'No matching product found'], 404);
    }
}

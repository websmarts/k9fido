<?php

namespace App\Http\Controllers;

use App\Legacy\Client\Client;
use App\Legacy\Product\ClientPrice;
use App\Legacy\Product\Product;
use Illuminate\Http\Request;

class ClientPricingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($clientId)
    {
        $items = ClientPrice::with('product')
            ->where('client_id', $clientId)
            ->get();

        // Transfrom prices into format for Vue.js app
        /**
         * id, product_code, client_price, std_price
         */
        $prices = $items->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'product_code' => $item->product_code,
                'client_price' => $item->client_price,
                'std_price' => $item->product->price,
            ];
        });
        // dd($prices);
        $client = Client::find($clientId);
        return view('admin.client.pricing', compact('prices', 'client'));
    }
    /**
     * Handles all the ajax requests for Client Pricing
     * Client based on Vue componet <client-prices>
     *
     * The action param determines the call type, actions include:
     *  - add_product - returns the requested product pricing data
     *
     * @method ajax
     * @param  Request $request [description]
     * @return [JSON]           [Various]
     */
    public function ajax(Request $request)
    {
        $action = $request->input('action');

        // If $action method exists then call/return it
        if (method_exists($this, $action)) {
            return $this->$action($request);
        }
        return ['result' => 404];

        // $clientId = $request->input('client_id');
        // $productCode = $request->input('product_code');
        // $price = $request->input('client_price');

        // $clientPrice = ClientPrice::firstOrNew([
        //     'client_id' => $clientId,
        //     'product_code' => $productCode,

        // ]);

        // if (is_null($clientPrice->product)) {
        //     return ['result' => 404];
        // }

        // $clientPrice->client_price = $price;

        // $clientPrice->save();

        // return $clientPrice;
    }

    protected function add_product(Request $request)
    {
        $productCode = $request->input('add_product_code');
        $product = Product::where('product_code', $productCode)->select('product_code', 'price')->get();
        if ($product) {
            return $product->toJson();
        }
        return ['result' => 404];
        // return collect($request->all())->toJson();
    }

}

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
        //$prices = $this->clientPrices($clientId);
        // dd($prices);
        $client = Client::find($clientId);
        return view('admin.client.pricing', compact('client'));
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

    }

    protected function get_client_prices(Request $request)
    {
        $clientId = $request->input('client_id');
        return $this->clientPrices($clientId);
    }

    protected function add_product(Request $request)
    {
        $productCode = $request->input('product_code');
        $price = $request->input('client_price');
        $clientId = $request->input('client_id');
        $updateClientIds = $this->getAllUpdateIds($clientId);

        foreach ($updateClientIds as $cid) {
            // get if already exists
            $clientPrice = ClientPrice::where([
                ['client_id', '=', $cid],
                ['product_code', '=', $productCode],
            ])->first();

            if (!$clientPrice) {
                $clientPrice = new ClientPrice;
                $clientPrice->client_id = $cid;
                $clientPrice->product_code = $productCode;
            }

            $clientPrice->client_price = $price;

            $clientPrice->save();
        }

        return $this->clientPrices($clientId);

    }

    protected function delete_product(Request $request)
    {
        $productCode = $request->input('product_code');
        $clientId = $request->input('client_id');
        $updateClientIds = $this->getAllUpdateIds($clientId);

        foreach ($updateClientIds as $cid) {
            ClientPrice::where([
                ['client_id', '=', $cid],
                ['product_code', '=', $productCode],
            ])->delete();
        }

        return $this->clientPrices($clientId);
    }

    protected function getAllUpdateIds($clientId)
    {
        $children = Client::where('parent', $clientId)->select('client_id')->get();

        $updateClientIds = $children->pluck('client_id')->all();
        $updateClientIds[] = $clientId;
        return $updateClientIds;
    }

    protected function update_prices(Request $request)
    {
        $clientId = $request->input('client_id');

        $updateClientIds = $this->getAllUpdateIds($clientId);

        // Update the ClientPrice record
        $updates = $request->input('updates');

        if (count($updates)) {
            foreach ($updates as $update) {
                foreach ($updateClientIds as $cid) {
                    $item = ClientPrice::where([
                        ['client_id', '=', $cid],
                        ['product_code', '=', $update['product_code']],
                    ])->update(['client_price' => $update['price']]);
                }

            }
        }

        return $this->clientPrices($clientId);
    }

    protected function clientPrices($clientId)
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

        return $prices;
    }

}

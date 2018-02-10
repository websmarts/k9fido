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
        // Pricing details handled by vue component on the page
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
        $clientId = $request->input('client_id');
        $discount = $request->input('discount');
        if ($discount > 1) {
            //Chances are the user addeed discount as a % instead of a decimal fraction
            // so convert it
            if ($discount > 100) {
                // What the !!! - give up
                return $this->clientPrices($clientId);
            }

            // This should be okay now
            $discount = $discount / 100;
        }

        $updateClientIds = $this->getAllUpdateIds($clientId);

        $product = Product::where('product_code', $productCode)->first();

        if (!$product) {
            // bad product code entered

            return $this->clientPrices($clientId);
        }

        if ($discount > 0) {
            // Valid - so delete all existing client prices for client family
            foreach ($updateClientIds as $cid) {
                ClientPrice::where([
                    ['client_id', '=', $cid],
                    ['product_code', '=', $productCode],
                ])->delete();
            }
        }

        foreach ($updateClientIds as $cid) {

            $clientPrice = ClientPrice::create([
                'client_id' => $cid,
                'product_code' => $productCode,
                'discount' => $discount,

            ]);

            // now add the std_price and the calculated client_price and then save
            $clientPrice->std_price = $clientPrice->product->price;
            $clientPrice->client_price = $clientPrice->product->price * (1 - $discount);

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

        // Delete all existing client_pricing info for
        // this client 'family'
        foreach ($updateClientIds as $cid) {
            ClientPrice::where('client_id', $cid)->delete();
        }

        // Update the ClientPrice record
        $updates = $request->input('updates');

        if (count($updates)) {
            foreach ($updates as $update) {
                foreach ($updateClientIds as $cid) {

                    $item = ClientPrice::create(
                        [
                            'client_id' => $cid,
                            'product_code' => $update['product_code'],
                            'discount' => $update['discount'],
                        ]
                    );

                    $item->std_price = $item->product->price;
                    $item->client_price = $item->product->price * (1 - $update['discount']);

                    $item->save();

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

        //return response()->json($items->toArray());

        // Transfrom prices into format for Vue.js app
        /**
         * id, product_code, client_price, std_price
         */
        $prices = $items->map(function ($item, $key) {
            // Check if item.product is null
            if ($item->product) {
                return [
                    'id' => $item->id,
                    'product_code' => $item->product_code,
                    'latest_std_price' => $item->product->price,
                    'client_price' => $item->client_price,
                    'std_price' => $item->std_price,
                    'discount' => $item->discount,
                ];
            } else {
                // A Product no longer exists with this product_code in product table
                // Delete do some cleanup and DELETE all client_prices with this product_code
                ClientPrice::where('product_code', $item->product_code)->delete();
            }

        });

        return $prices;
    }

}

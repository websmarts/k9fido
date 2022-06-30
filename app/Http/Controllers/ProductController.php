<?php

namespace App\Http\Controllers;

use App\Legacy\Product\ClientPrice;
use App\Legacy\Product\Product;
use App\Legacy\Product\ProductType;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        
       

        $products = Product::with('bom')->applyUserFilter()
            ->select('id', 'description', 'product_code', 'status')
            ->orderBy('product_code', 'asc')
            ->paginate(15);



        // Get total sales of product in sales_period
        if( session()->has('sales_period')) {
            $salesPeriod = session('sales_period');
            foreach($products as $product) {
                $product->salestotal = $product->recentSales($salesPeriod)->sales;
                $product->salesunits = $product->recentSales($salesPeriod)->units;

            }
        }


        
            
        

        $filterKey = 'products';

        return view('admin.product.index', compact('products', 'filterKey'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $product = new Product;
        if ($request->has('typeid')) {
            $product->typeid = $request->get('typeid');
        }

        $productTypes = $this->getProductTypes();
        // return $productTypes->toArray();

        return view('admin.product.create', compact('product', 'productTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_code' => 'required',
            'typeid' => 'required',
            'description' => 'required',

        ]);
        // Should check if product code already exists
        // before creating a new entry.

        $product = Product::where('product_code',$request->input('product_code'))->first();

        if(!$product){
            Product::create($request->all());

            flash('Product saved', 'success');
            return redirect()->route('type.index');

        } 

        flash('Product code already exists');
         return redirect()->back();

        

        

        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $product = Product::find($id);

        $productTypes = $this->getProductTypes();

        // Get the total of items ordered on all un-picked orders
        // get list of all any system_order_items for this product where
        // the status of the order status is not 'picked'
        $sql = 'SELECT SUM(SOI.qty) AS ordered FROM
                system_orders SO
                LEFT JOIN system_order_items SOI ON SO.order_id = SOI.order_id
                WHERE SO.status IN ("saved","parked","printed")
                AND SOI.product_code = ? ';
        $qty = \DB::connection('k9homes')->select($sql, [$product->product_code]);

        $product->qty_ordered = $qty[0]->ordered;

        // fabricate shelf qty as  = available + ordered
        $product->qty_onshelf = $product->qty_instock + $product->qty_ordered;

        // dd($product);
        // Clients with a special price for this product
        $cps = $product->clientprices;
        //dd($cps);
        $clients = [];
        foreach ($cps as $cp) {
            // $clients[$cp->client_id] = Client::find($cp->client_id);
            // ignore children
            $c = $cp->client;
            if (!$c) {
                ClientPrice::where('client_id', $cp->client_id)->delete();
                continue;
            }

            if ($c && $c->parent == 0) {
                $clients[$cp->client_id] = $c;
            }

        }
        //dd($clients);

        return view('admin.product.edit', compact('product', 'productTypes', 'clients'));
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
        // dd($request->all());

        $product = Product::find($id);

        // check if delete is being called for
        if ($request->_delete == $id) {

            // Delete the product itself
            $product->delete();

            // TODO Delete any special PRICING

            return redirect()->route('product.index');
        }

        $this->validate($request, ['description' => 'required']);

        $data = $request->all();
        //dd($data);

        // recalc instock figure by subtracting the qty ordered from the onshelf value, unless it is a bom product
        if(isSet($data['qty_onshelf'])){
            
            $data['qty_instock'] = (int) $data['qty_onshelf'] - (int) $data['qty_ordered'];
            unset($data['qty_onshelf']);

        }
       

        // Reset the calculated displayed value of qty_ordered to zero
        $data['qty_ordered'] = 0;

        // set the shipping_volume and shipping_weight

        $length = isSet($data['length']) ? (float) $data['length'] : 0;
        $width = isSet($data['width']) ? (float) $data['width'] : 0;
        $height = isSet($data['height']) ? (float) $data['height'] : 0;
        $shipping_volume = isSet($data['shipping_volume']) ? (float) $data['shipping_volume'] : 0;

        $data['shipping_volume'] = $length * $width * $height / 1000000;
        $data['shipping_weight'] = $shipping_volume * 250;

        


        $product->update($data);
/** 
        // TODO Check if pricing has changed and if so update all special pricing for product
        $clientPrices = $product->clientprices()->get();
        //dd($clientPrices);

        $clientPrices->each(function($clientPrice,$key) {
            if($clientPrice->product->price != $clientPrice->std_price){
                // now add the std_price and the calculated client_price and then save
                $clientPrice->std_price = $clientPrice->product->price;
                $clientPrice->client_price = $clientPrice->product->price * (1 - $clientPrice->discount);
                

                $clientPrice->save();
                //dd($clientPrice);
            }
        });
*/

        flash('Product updated ...', 'success');

        return redirect()->route('product.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function bulkUpdate(Request $request)
    {
        
        
        
        $productCodes = array_keys( $request->input('product',[]) ) ;


        foreach($productCodes as $productCode) {
            // set product status to inactive
            $product = Product::where('product_code',$productCode)->first();
            $product->status = $request->input('status', 'active');
            $product->save();
        }

        return redirect()->back();
    }

/**
 * Get orders where product is on order
 * @method orders
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
    public function orders($id)
    {
        $product = Product::find($id);

        $sql = 'SELECT soi.* FROM system_orders so
                LEFT JOIN system_order_items soi ON so.order_id = soi.order_id
                WHERE so.`status` != "picked" AND soi.qty - soi.qty_supplied > 0
                AND soi.product_code = ?';
        $orders = \DB::connection('k9homes')->select($sql, [$product->product_code]);

        return view('admin.product.orders', compact('orders', 'product'));
    }

    protected function getProductTypes()
    {
        return ProductType::orderBy('name', 'asc')->lists('name', 'typeid')->toArray();
    }

    public function setSalesPeriod()
    {
        $key = 'sales_period';
        $period = request($key);


        if($period) {
            request()->session()->put($key,$period);
        } else {
            request()->session()->forget($key);
        }

        return redirect()->back();
        
        
    }

    public function regenClientPrices()
    {
        $products = Product::where('status','=', 'active')->get();
        echo ' Starting ....<br>';
        echo'Product:Client ID<br>';
         $products->each(function($product,$key){
            
             foreach($product->clientPrices as $clientPrice){
        //         //dd([$product->product_code, $clientPrice->std_price]);
                echo $product->product_code .'STD Price:'.$product->price .'Client price:'.$clientPrice->std_price.'<br>';
                 if($product->price != $clientPrice->std_price) {
            
                    echo 'updating '.$product->product_code. ' for client ID '.$clientPrice->client_id .'<br>';
                    // now add the std_price and the calculated client_price and then save
                    $clientPrice->std_price = $product->price;
                    $clientPrice->client_price = $product->price * (1 - $clientPrice->discount);
                    
                    $clientPrice->save();
                    echo $product->product_code .':' .$clientPrice->client_id .'<br>';

                    dd('done done');
                }
                
             
             }
            

         });
        echo '<br> All done ... active products prices have been synced with client prices '."\n";
        
        
    }
}

<?php

namespace App\Http\Controllers;

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
        $products = Product::applyUserFilter()
            ->select('id', 'description', 'product_code', 'status')
            ->orderBy('product_code', 'asc')
            ->paginate(15);

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

        Product::create($request->all());

        flash('Product saved', 'success');

        return redirect()->route('type.index');
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
        $sql = 'SELECT SUM(soi.qty - soi.qty_supplied) as qty_ordered FROM system_orders so
                LEFT JOIN system_order_items soi ON so.order_id = soi.order_id

                WHERE so.`status` != "picked"
                AND soi.product_code = ?';
        $qty = \DB::connection('k9homes')->select($sql, [$product->product_code]);

        $product->qty_ordered = $qty[0]->qty_ordered;
        // dd($product);

        return view('admin.product.edit', compact('product', 'productTypes'));
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
        $product = Product::find($id);

        // check if delete is being called for
        if ($request->_delete == $id) {

            // Delete the product itself
            $product->delete();

            return redirect()->route('product.index');
        }

        $this->validate($request, ['description' => 'required']);

        $data = $request->all();
        //dd($data);
        // Reset the displayed value of qty_ordered to zero
        $data['qty_ordered'] = 0;

        $product->update($data);

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

    protected function getProductTypes()
    {
        return ProductType::orderBy('name', 'asc')->lists('name', 'typeid')->toArray();
    }
}

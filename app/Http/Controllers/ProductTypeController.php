<?php

namespace App\Http\Controllers;

use App\Legacy\Product\ProductType;
use DB;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
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
    public function index()
    {

        $types = ProductType::filtered()->orderBy('typeid', 'desc')->simplePaginate(15);

        return view('admin.type.index')->with('types', $types);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $type = new ProductType;

        return view('admin.type.create')->with('type', $type);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, ['display_format' => 'required']);

        ProductType::create($request->all());

        flash('Type saved', 'success');

        return redirect()->route('type.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // products closure adds the orderBy to the eager load
        $type = ProductType::with(['categories', 'options' => function ($query) {

            $query->orderBy('opt_code', 'asc');

        }, 'products' => function ($query) {

            $query->orderBy('product_code', 'asc');

        }])->find($id);

        return view('admin.type.edit')->with('type', $type);

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

        $type = ProductType::find($id);

        // check if delete is being called for
        if ($request->_delete == $id) {
            // TODO
            // Delete Products of this type
            DB::connection('k9homes')->table('products')->where('typeid', '=', $id)->delete();

            // Delete Type Category entries
            DB::connection('k9homes')->table('type_category')->where('typeid', '=', $id)->delete();

            // Delete type options for this type
            DB::connection('k9homes')->table('type_options')->where('typeid', '=', $id)->delete();

            // Delete the type itself
            $type->delete();

            flash('Type deleted', 'success');

            return redirect()->route('type.index');
        }

        $this->validate($request, ['display_format' => 'required']);

        $type->update($request->all());

        flash('Type updated', 'success');

        return redirect()->route('type.index', $id);
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

    protected function filter()
    {
        if ($fd = session('__FILTER__type')) {
            $where = ['name', 'like', '%' . $fd . '%'];
        } else {
            $where = ['typeid', '>', 0];
        }
        return [$where];
    }
}

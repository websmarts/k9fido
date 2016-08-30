<?php

namespace App\Http\Controllers;

use App\Legacy\Product\ProductType;
use App\Legacy\Product\ProductTypeOption;
use Illuminate\Http\Request;

class ProductTypeOptionController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $typeid = $request->get('typeid');

        $type = ProductType::with('options')->find($typeid);

        return view('admin.type.create_option')->with('type', $type);
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
            'opt_code' => ['required', 'regex:/^[a-z]{3}$/i'],
            'opt_desc' => 'required',
            'opt_class' => ['required', 'regex:/^opt_([a-f0-9]{3}){1,2}$/i'],
        ]);

        // should really check if the opt_code exists and do an update if it does
        // and only create if it doesn't

        ProductTypeOption::create($request->all());

        return redirect()->route('type.edit', ['id' => $request->typeid]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($typeid, $opt)
    {

        $typeOption = ProductTypeOption::where(['typeid' => $typeid, 'opt_code' => $opt])->first();

        return view('admin.type.edit_option')->with('typeOption', $typeOption);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $typeid, $opt)
    {
        $option = ProductTypeOption::where(['typeid' => $typeid, 'opt_code' => $opt])->first();

        // check if delete is being called for
        if ($request->_delete == $typeid) {
            // TODO do we delete products that match this option
            // NO verticals can have extension and we dont want to delete them
            // as they are not really related to this option notion
            $option->delete();
            return redirect()->route('type.edit', ['id' => $typeid]);
        }

        // $this->validate($request, [
        //     'opt_code' => ['required', 'regex:/^[a-z]{3}$/i'],
        //     'opt_desc' => 'required',
        //     'opt_class' => ['required', 'regex:/^opt_([a-f0-9]{3}){1,2}$/i'],
        // ]);

        $option->update($request->all());

        return redirect()->route('type.edit', ['id' => $typeid]);
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
}

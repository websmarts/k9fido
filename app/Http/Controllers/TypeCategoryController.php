<?php

namespace App\Http\Controllers;

use App\Legacy\Category\Category;
use App\Legacy\Procuct\ProductType;
use DB;
use Illuminate\Http\Request;

class TypeCategoryController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
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
    public function edit($id)
    {
        $categories = Category::topLevel()->with('children')->orderBy('display_order', 'desc')->get();

        $type = ProductType::with('categories')->find($id);

        return view('admin.type.type_categories', [
            'categories' => $categories,
            'type' => $type,
        ]);

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

        DB::connection('k9homes')->table('type_category')->where('typeid', '=', $id)->delete();

        $categories = collect($request->categories);

        if ($categories->count()) {
            foreach ($categories as $catId) {

                DB::connection('k9homes')
                    ->insert('insert into type_category (catid, typeid) values (?, ?)', [$catId, $id]);
            }
        }

        flash('Updated');
        return redirect()->route('typecategory.edit', $id);

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

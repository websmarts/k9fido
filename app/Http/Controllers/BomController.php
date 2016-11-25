<?php

namespace App\Http\Controllers;

use App\Legacy\Product\Bom;
use Illuminate\Http\Request;

class BomController extends Controller
{
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
        //
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
        $bom = Bom::where('parent_product_code', $id)->orderby('item_product_code', 'asc')->get();
        // dd($bom);
        return view('admin.bom.edit', compact('bom', 'id'));
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
        //
        $items = collect($request->input('item'));
        foreach ($items as $k => $i) {
            if ($k < 0) {
                $k = null;
            }

            $i['parent_product_code'] = $id;

            // Delete an existing item if qty is zero or less
            if ($i['item_qty'] < 1) {
                if (!is_null($k)) {
                    Bom::destroy($k);
                }
            }

            // Update item or create if product_code and qty
            if ($i['item_qty'] > 0 && !empty($i['item_product_code'])) {
                $bom = Bom::updateOrCreate(['id' => $k], $i);
            }

        }

        flash('Bom updated ...', 'success');
        return redirect()->route('bom.edit', $id);
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

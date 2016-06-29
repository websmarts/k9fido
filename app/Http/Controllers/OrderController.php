<?php

namespace App\Http\Controllers;

use DB;

class OrderController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = DB::connection('k9homes')->table("system_orders")
            ->where('status', '=', 'printed')
            ->orderBy('id', 'asc')
            ->simplePaginate(15);
        // dd($users);

        return view('admin/sales/index')->with('orders', $orders);
    }
}

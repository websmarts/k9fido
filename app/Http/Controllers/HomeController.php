<?php

namespace App\Http\Controllers;

use App\Legacy\Order\Order;
use Carbon\Carbon;

class HomeController extends Controller
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
        // $users = DB::connection('k9homes')->select("select * from type");
        // dd($users);
        $orders = Order::with('client')
            ->whereIn('status', ['saved', 'printed', 'parked', 'picked', 'basket'])
            ->whereDate('modified', '>=', Carbon::today()->subDays(90)->toDateString())
            ->where('exported', '!=', 'yes')
            ->orderBy('id', 'asc')
            ->get();

        $basketOrders = $orders->where('status', 'basket');
        $newOrders = $orders->where('status', 'saved');
        $pickOrders = $orders->where('status', 'printed');
        $parkOrders = $orders->where('status', 'parked');
        $exportOrders = $orders->where('status', 'picked');

        return view('home', compact('newOrders', 'pickOrders', 'parkOrders', 'exportOrders', 'basketOrders'));
    }
}

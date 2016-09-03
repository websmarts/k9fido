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
            ->whereIn('status', ['new', 'printed', 'basket'])
            ->whereDate('modified', '>=', Carbon::today()->subDays(90)->toDateString())

            ->orderBy('id', 'asc')
            ->get();

        $newOrders = $orders->where('status', 'saved');
        $pickOrders = $orders->where('status', 'printed');
        $basketOrders = $orders->where('status', 'basket');

        return view('home', compact('newOrders', 'pickOrders', 'basketOrders'));
    }
}

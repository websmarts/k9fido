<?php

namespace App\Http\Controllers;

use DB;

class ClientController extends Controller
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
        $clients = DB::connection('k9homes')->table("clients")->simplePaginate(15);
        // dd($users);

        return view('admin/clients/index')->with('clients', $clients);
    }
}

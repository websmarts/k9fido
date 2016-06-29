<?php

namespace App\Http\Controllers;

use DB;

class StaffController extends Controller
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
        $staff = DB::connection('k9homes')->select("select * from users");
        // dd($users);

        return view('admin/staff/index')->with('staff', $staff);
    }
}

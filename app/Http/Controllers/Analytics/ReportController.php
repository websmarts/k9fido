<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;

class ReportController extends Controller
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

    public function index()
    {
        return view('admin.reports.index');
    }
}

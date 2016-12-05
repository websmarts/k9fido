<?php

namespace App\Http\Controllers\Prospector;

use App\Http\Controllers\Controller;

class AccountsController extends Controller
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
        return view('admin.prospector.accounts.index');
    }
}

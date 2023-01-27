<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PagesController extends Controller
{
    public function index($slug)
    {
        // find the page by slug or 404 error
        // $filepath = base_path('resources/views/pages');
        // dd($filepath);

        // display the page
        return view('pages.'.$slug);
    }
}

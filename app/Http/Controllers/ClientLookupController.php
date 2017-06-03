<?php

namespace App\Http\Controllers;

use App\Legacy\Client\Client;
use Illuminate\Http\Request;

class ClientLookupController extends Controller
{
    //
    public function index()
    {
        $query = request('q');

        $results = Client::where('name', 'like', '%' . $query . '%')
            ->select(['name', 'city', 'postcode', 'state'])
            ->limit(20)
            ->get();

        return $results;
    }
}

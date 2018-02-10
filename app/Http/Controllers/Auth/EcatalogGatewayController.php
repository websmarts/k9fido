<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EcatalogGatewayController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->u;
        $url = $request->url;

        // Find and Login the user
        $user = User::where('name', $user)->first();
        if ($user) {
            Auth::login($user);

            // Redirect to where they wanted to go
            return redirect($url);
        }

        dd('BAD GATEWAY REQUEST');

    }
}

<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

use DB;

Route::get('/', function () {
    $users = DB::connection('k9homes')->select("select * from users");
    dd($users);

    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

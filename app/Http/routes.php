<?php

// \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
//     echo '<pre>';
//     var_dump($query->sql);
//     echo '</pre>';
//     // var_dump($query->bindings);
//     // var_dump($query->time);
// });

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

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');
Route::get('/staff', 'StaffController@index');
Route::get('/clients', 'ClientController@index');
Route::get('/sales', 'OrderController@index');

Route::resource('type', 'TypeController');

Route::resource('typeoption', 'TypeOptionController');

Route::resource('product', 'ProductController');

Route::resource('category', 'CategoryController');

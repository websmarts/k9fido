<?php

use App\ProductTypeImage;
use Illuminate\Http\Request;

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
// sorts a productTypeImage list
Route::post('ajax/image/sort', function (Request $request) {
    $items = $request->get('item');
    $order = 0;
    foreach ($items as $item) {
        $image = ProductTypeImage::find($item);
        $image->order = $order++;
        $image->save();
    }
    return $items;

});

Route::get('/', function () {

    //return \Auth::guard('web')->user();
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');
// Route::get('/staff', 'StaffController@index');
// Route::get('/clients', 'ClientController@index');
// Route::get('/sales', 'OrderController@index');

Route::post('filter/{name}', 'FilterController@index');

Route::resource('product', 'ProductController');

Route::resource('type', 'ProductTypeController');

Route::resource('typeoption', 'ProductTypeOptionController');
Route::get('typeoption/{typeid}/{opt}', [
    'as' => 'typeoption.edit', 'uses' => 'ProductTypeOptionController@edit']);
Route::post('typeoption/{typeid}/{opt}', [
    'as' => 'typeoption.update', 'uses' => 'ProductTypeOptionController@update']);

Route::resource('category', 'CategoryController');
Route::get('category/{id}/delete', [
    'as' => 'category.delete', 'uses' => 'CategoryController@delete']);

Route::resource('typecategory', 'TypeCategoryController');

Route::resource('order', 'OrderController');
Route::get('orderitem/{orderId}/{productCode}/edit', [
    'as' => 'order.edititem', 'uses' => 'OrderController@editOrderItem']);
Route::get('order/{orderId}/delete', [
    'as' => 'order.delete', 'uses' => 'OrderController@destroy']);

Route::resource('client', 'ClientController');
Route::resource('staff', 'StaffController');

Route::resource('producttypeimage', 'ProductTypeImageController');

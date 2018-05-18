<?php

// \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
//     echo '<pre>';
//     var_dump($query->sql);
//     echo '</pre>';
//     var_dump($query->bindings);
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

Route::get('testy/', 'ClientPricingController@add_product');

Route::get('test/{typeid}', 'ProductTypeImageController@doSortIfRequired');

// Route::get('reports', 'ProductReportsController@index');

// sorts a productTypeImage list

// Route::get('/', function () {

//     //return \Auth::guard('web')->user();
//     return view('welcome');
// });

Route::auth();
Route::get('ecatgw', 'Auth\EcatalogGatewayController@index'); // links from eCat
Route::get('/', ['as' => 'welcome', 'uses' => 'HomeController@welcome']);
Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);

// FILTER LISTS
Route::post('filter/{name}', 'FilterController@index');

// PRODUCT ROUTES
Route::resource('product', 'ProductController');
Route::get('product/{id}/orders', 'ProductController@orders');

// BOM ROUTES
Route::resource('bom', 'BomController');

// PRODUCT TYPE , TYPE_OPTION , TYPE IMAGES
Route::resource('type', 'ProductTypeController');
Route::resource('typeoption', 'ProductTypeOptionController');
Route::get('typeoption/{typeid}/{opt}', [
    'as' => 'typeoption.edit', 'uses' => 'ProductTypeOptionController@edit']);
Route::post('typeoption/{typeid}/{opt}', [
    'as' => 'typeoption.update', 'uses' => 'ProductTypeOptionController@update']);
// PRODUCT TYPE IMAGE ROUTES
Route::resource('producttypeimage', 'ProductTypeImageController');
// ajax handlers for sort, upload and deleting of product images
Route::post('ajax/image/sort', 'ProductTypeImageController@sort');
Route::post('ajax/image/upload/{typeid}', 'ProductTypeImageController@upload');
Route::post('ajax/image/delete/{imageid}', 'ProductTypeImageController@delete');

// CATEGORY ROUTES
Route::resource('category', 'CategoryController');
Route::get('category/{id}/delete', [
    'as' => 'category.delete', 'uses' => 'CategoryController@delete']);

// TYPE_CATEGORY ROUTES
Route::resource('typecategory', 'TypeCategoryController');

//ORDER ROUTES
Route::resource('order', 'OrderController');
Route::get('order/{id}/pick', [
    'as' => 'order.pick', 'uses' => 'OrderController@pick']);

// ORDER EXPORT ROUTES
Route::get('export/{id}/export', [
    'as' => 'export.myob', 'uses' => 'ExportController@export']);
Route::post('export/batchexport', [
    'as' => 'export.batchexport', 'uses' => 'ExportController@batchexport']);
Route::get('export/{id}/detail', [
    'as' => 'export.detail', 'uses' => 'ExportController@detail']);
Route::get('export/download', [
    'as' => 'export.download', 'uses' => 'ExportController@download']);

//ORDER ITEM ROUTES
Route::get('orderitem/{orderId}/{productCode}/edit', [
    'as' => 'order.edititem', 'uses' => 'OrderController@editOrderItem']);
Route::get('order/{orderId}/delete', [
    'as' => 'order.delete', 'uses' => 'OrderController@destroy']);
// PICKING ORDERS
Route::get('ajax/pickorder/{id}', 'OrderController@pickorderGet');
Route::post('ajax/pickorder/{id}', 'OrderController@pickorderStore');

//CLIENT ROUTES
Route::resource('client', 'ClientController');

// CLIENT PRICING ROUTES
Route::get('client/{id}/pricing', ['as' => 'client.pricing', 'uses' => 'ClientPricingController@index']);
//Route::post('client/{id}/pricing', ['as' => 'client.pricing', 'uses' => 'ClientController@storePricing']);
Route::post('ajax/client/price', ['as' => 'client.price.ajax', 'uses' => 'ClientPricingController@ajax']);

// STAFF ROUTES
Route::resource('staff', 'StaffController');

//PROSPECTOR
Route::get('prospector', [
    'as' => 'prospector.index', 'uses' => 'Prospector\HomeController@index']);
Route::get('prospector/accounts', [
    'as' => 'prospector.accounts.index', 'uses' => 'Prospector\AccountsController@index']);

// Freight Calculator
// Route::get('freight/location', [
//     'as' => 'freight.location', 'uses' => 'FreightCalculatorController@searchForLocation']); // ajax data for location autocomplete
// Route::get('freight', [
//     'as' => 'freight.index', 'uses' => 'FreightCalculatorController@index']);
// Route::post('freight/quote', [
//     'as' => 'freight.quote', 'uses' => 'FreightCalculatorController@quote']);

// Client search for ajax autocomplete selectors
Route::get('clientlookup/', [
    'as' => 'clientlookup', 'uses' => 'ClientLookupController@index']); // ajax data for client lookup autocomplete

// test route for freight testing
Route::get('freight/{postcode?}', 'FreightController@index');

// Stock Adjuster
Route::get('stockadjust', 'StockAdjustController@index');
Route::post('stockadjust/find', 'StockAdjustController@find');
Route::post('stockadjust/{product}', 'StockAdjustController@update');

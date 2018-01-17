<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();

});
Route::get('/category' , 'CategoryController@index');
Route::get('/slider','CategoryController@slider');
Route::resource('itemCategory', 'CategoryProductController');
Route::get('/search/{name}' , 'CategoryProductController@byName');
Route::get('/filterPriceCategory' , 'CategoryProductController@filterByPriceFromCategory');
Route::get('/filterPriceName' , 'CategoryProductController@filterByPriceFromName');
Route::get('/filtrarFecha','CategoryProductController@busquedaPorFecha');
Route::get('/itemProduct/{id}','ProductController@show');
Route::get('/getCategory','ProductController@index');
Route::post('register','customerController@registerCustomer');
Route::post('product','ProductController@addProduct');
Route::post('loginCustomer/','customerController@loginCustomer');

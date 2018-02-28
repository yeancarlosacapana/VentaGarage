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

Route::resource('product','ProductController');
Route::post('product','ProductController@addProduct');
Route::post("product/customer","ProductController@getProductByCustomer");

Route::resource('itemCategory', 'CategoryProductController');

Route::get('/category' , 'CategoryController@index');
Route::get('/slider','CategoryController@slider');
Route::get('/search/{name}' , 'CategoryProductController@byName');
Route::get('/filterPriceCategory' , 'CategoryProductController@filterByPriceFromCategory');
Route::get('/filterPriceName' , 'CategoryProductController@filterByPriceFromName');
Route::get('/filtrarFecha','CategoryProductController@busquedaPorFecha');
Route::get('/subcategoria/{id_category}','CategoryProductController@showCategoryByIdCategory');
Route::get('/itemProduct/{id}','ProductController@show');
Route::get('/getCategory','ProductController@index');
Route::post('register','customerController@registerCustomer');
Route::post('loginCustomer/','customerController@loginCustomer');
Route::post('loginSocial/','customerController@loginSocial');
Route::get('/state', 'StateController@index');
Route::get('/provincia/{id_state}', 'ProvinciaController@index');
Route::get('distrito/{id_provincia}', 'DistritoController@index');

Route::post("culqi/payout","CulqiController@payout");

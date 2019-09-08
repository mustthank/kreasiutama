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

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});
Route::group(['middleware' => 'jwt.auth'], function ($router) {
    Route::get('customers', 'CustomersController@all');
    Route::get('customers/{id}', 'CustomersController@get');
    Route::post('customers/new', 'CustomersController@create');
    Route::delete('customers/delete/{id}', 'CustomersController@delete');
    
    Route::get('datatransaksi', 'TransaksiController@all');
    Route::post('datatransaksi/new', 'TransaksiController@create');

    Route::get('tmptransaksi', 'TransaksiController@tmp');
    Route::delete('tmptransaksi/{id}', 'TransaksiController@deletetmp');
    Route::post('tmptransaksi/delete', 'TransaksiController@deleteall');
    Route::post('datatransaksi/save', 'TransaksiController@save');

    Route::get('tmppembayaran', 'PembayaranController@tmppembayaran');
    Route::post('pembayaran/create', 'PembayaranController@create');
    Route::post('pembayaran/delete', 'PembayaranController@deleteall');
    Route::post('pembayaran/save', 'PembayaranController@store');

});
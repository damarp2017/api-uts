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

Route::post('uts','ApiController@store');
Route::get('uts','ApiController@index');
Route::post('uts/{id}','ApiController@update');
Route::get('uts/{id}','ApiController@show');
Route::delete('uts/{id}', 'ApiController@destroy');
Route::post('uts/search/result', 'ApiController@search');

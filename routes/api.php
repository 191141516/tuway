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

Route::group(['namespace' => 'Api', 'as' => 'api.'], function () {

    Route::get('wx-login', 'AuthenticateController@wxLogin')->name('wx-login');

    Route::group(['middleware' => ['auth:api']] , function () {
        Route::post('save-user-info', 'UserController@saveUserInfo')->name('save-user-info');
//    return Auth::guard('api')->user();
//    return $request->user();
    });

});





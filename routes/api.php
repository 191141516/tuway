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

        //用户
        Route::group(['prefix' => 'user', 'as' => 'user.'], function(){
            //保存用户信息
            Route::post('save-user-info', 'UserController@saveUserInfo')->name('save-user-info');

        });

        //活动
        Route::group(['prefix' => 'activity', 'as' => 'activity.'], function(){
            //发布活动
            Route::post('/', 'ActivityController@create')->name('create');
            //活动列表
            Route::get('/', 'ActivityController@index')->name('list');
            //活动详情
            Route::get('/{id}', 'ActivityController@detail')->name('detail');
            //活动修改
            Route::put('/{id}', 'ActivityController@edit')->name('edit');
        });

        //报名
        Route::group(['prefix' => 'entry', 'as' => 'entry.'], function(){
            Route::post('/', 'EntryController@create')->name('create');
        });

        //上传
        Route::group(['prefix' => 'upload', 'as' => 'upload.'], function(){
            Route::post('img', 'UploadController@img')->name('img');
        });

        //必选项
        Route::group(['prefix' => 'option', 'as' => 'option.'], function(){
            Route::get('/', 'OptionController@index')->name('list');
        });
    });

});





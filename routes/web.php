<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    abort(403, '');
//});

//后台路由
Route::group(['middleware' => ['web'],'namespace' => 'Admin', 'as' => 'admin.', 'prefix' => 'admin'], function(){
    Route::get('/login', 'LoginController@showLoginForm')->name('show-login');
    Route::post('/login', 'LoginController@login')->name('login');

    Route::group(['middleware' => ['auth:admin']], function(){
        //日志
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

        Route::get('/logout', 'LoginController@logout')->name('logout');

        Route::get('/', 'DashboardController@index')->name('dashboard');

        //用户管理
        Route::group(['prefix' => 'user', 'as' => 'user.'], function(){
            Route::get('/', 'UserController@index')->name('index');

            Route::get('/ajax', 'UserController@ajax')->name('ajax');

            Route::put('/update-status', 'UserController@updateStatus')->name('update-status');
        });


        //活动管理
        Route::group(['prefix' => 'activity', 'as' => 'activity.'], function(){
            Route::get('/', 'ActivityController@index')->name('index');

            Route::get('/ajax', 'ActivityController@ajax')->name('ajax');

            Route::put('/update-state', 'ActivityController@updateState')->name('update-state');

            Route::delete('/{id}', 'ActivityController@destroy')->name('delete');

            Route::get('/{id}', 'ActivityController@info')->name('detail');

            Route::put('/{id}/top', 'ActivityController@top')->name('top');

            Route::put('/{id}/cancel-top', 'ActivityController@cancelTop')->name('cancel-top');
        });

        //运营用户管理
        Route::group(['prefix' => 'operate-account', 'as' => 'operate-account.'], function(){
            Route::get('/', 'OperateAccountController@index')->name('index');

            Route::get('/ajax', 'OperateAccountController@ajax')->name('ajax');

            Route::put('/update-status', 'OperateAccountController@updateStatus')->name('update-status');

            Route::post('/', 'OperateAccountController@create')->name('create');

            Route::get('/{id}', 'OperateAccountController@detail')->name('detail');

            Route::put('/{id}', 'OperateAccountController@update')->name('update');
        });

        //上传
        Route::group(['prefix' => 'upload', 'as' => 'upload.'], function(){
            Route::post('img', 'UploadController@img')->name('img');
        });
    });
});
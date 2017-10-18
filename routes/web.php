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

            Route::get('/ajax', 'UserController@ajax')->name('index');

            Route::put('/update-status', 'UserController@updateStatus')->name('update-status');
        });


        //活动管理
        Route::group(['prefix' => 'activity', 'as' => 'activity.'], function(){
            Route::get('/', 'ActivityController@index')->name('index');

            Route::get('/ajax', 'ActivityController@ajax')->name('index');

            Route::put('/update-state', 'ActivityController@updateState')->name('update-state');

            Route::delete('/{id}', 'ActivityController@destroy')->name('delete');

            Route::get('/{id}', 'ActivityController@info')->name('detail');
        });
    });
});
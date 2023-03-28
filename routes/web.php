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

Route::get('/', function () {
    return redirect('login');
});

//Route::get('/test', 'TestController@test');

Auth::routes();

Route::group(['middleware' => [
    'auth',
    ]], function () {

    Route::group(['middleware' => ['permission:' . \App\Uavsms\UserRole\Permission::CAN_ACCESS_PANEL]], function () {

        Route::get('/dashboard', 'HomeController@index')->name('home');

        Route::group(['middleware' => ['permission:' . \App\Uavsms\UserRole\Permission::CAN_ACCESS_PANEL]], function () {

            Route::get('/admin-users', 'UserController@index');
            Route::post('/admin-users/search', 'UserController@search');
        });

        Route::group(['middleware' => ['permission:' . \App\Uavsms\UserRole\Permission::CAN_MANAGE_USERS]], function () {

            Route::get('/admin-users/create', 'UserController@create');
            Route::post('/admin-users', 'UserController@store');

        });
    });
});

Route::get('tools/per-page/{per_page}', 'ToolsController@perPage');

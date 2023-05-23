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

Route::get('/test', 'TestController@test');

Auth::routes();

Route::group(['middleware' => [
    'auth',
    ]], function () {

    Route::group(['middleware' => ['permission:' . \App\Uavsms\UserRole\Permission::CAN_ACCESS_PANEL]], function () {

        Route::get('/dashboard', 'HomeController@index')->name('home');

        Route::group(['middleware' => ['permission:' . \App\Uavsms\UserRole\Permission::CAN_ACCESS_PANEL]], function () {

            Route::get('/users/{type}', 'UserController@index');
            Route::post('/users/{type}/search', 'UserController@search');

            Route::get('/uavs', 'UavController@index');
            Route::post('/uavs/search', 'UavController@search');

            Route::get('/charging-companies', 'ChargingCompanyController@index');
            Route::post('/charging-companies/search', 'ChargingCompanyController@search');

            Route::get('/charging-stations', 'ChargingStationController@index');
            Route::post('/charging-stations/search', 'ChargingStationController@search');

        });

        Route::group(['middleware' => ['permission:' . \App\Uavsms\UserRole\Permission::CAN_VIEW_USERS]], function () {

            Route::get('/users/{type}/{user_id}/view', 'UserController@view');

        });

        Route::group(['middleware' => ['permission:' . \App\Uavsms\UserRole\Permission::CAN_MANAGE_USERS]], function () {

            Route::get('/users/{type}/create', 'UserController@create');
            Route::post('/users/{type}', 'UserController@store');

            Route::get('/users/{type}/{user_id}/edit', 'UserController@edit');
            Route::put('/users/{type}/{user_id}', 'UserController@save');

            Route::delete('/users/{type}/{user_id}', 'UserController@delete');

        });

        Route::group(['middleware' => ['permission:' . \App\Uavsms\UserRole\Permission::CAN_VIEW_UAVS]], function () {

            Route::get('/uavs/{id}/view', 'UavController@view');

        });

        Route::group(['middleware' => ['permission:' . \App\Uavsms\UserRole\Permission::CAN_MANAGE_UAVS]], function () {

            Route::get('/uavs/create', 'UavController@create');
            Route::post('/uavs', 'UavController@store');

            Route::get('/uavs/{id}/edit', 'UavController@edit');
            Route::put('/uavs/{id}', 'UavController@save');

            Route::delete('/uavs/{id}', 'UavController@delete');

        });

        Route::group(['middleware' => ['permission:' . \App\Uavsms\UserRole\Permission::CAN_VIEW_STATIONS]], function () {

            Route::get('/charging-stations/{id}/view', 'ChargingStationController@view');

        });

        Route::group(['middleware' => ['permission:' . \App\Uavsms\UserRole\Permission::CAN_MANAGE_STATIONS]], function () {

            Route::get('/charging-stations/create', 'ChargingStationController@create');
            Route::post('/charging-stations', 'ChargingStationController@store');

            Route::get('/charging-stations/{id}/edit', 'ChargingStationController@edit');
            Route::put('/charging-stations/{id}', 'ChargingStationController@save');

        });
    });
});

Route::get('tools/per-page/{per_page}', 'ToolsController@perPage');

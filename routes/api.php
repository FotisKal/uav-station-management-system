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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['middleware' => ['auth:api']], function () {
    
    Route::post('/charging-sessions/start', 'ChargingSessionController@apiStore');
    Route::put('/charging-sessions/stop', 'ChargingSessionController@apiStop');

    Route::put('/charging-stations', 'ChargingStationController@apiSave');

});

Route::group(['middleware' => ['auth:api_uavs']], function () {

    Route::put('/uavs/position', 'UavController@apiSavePosition');

    Route::put('/uavs/battery-level', 'UavController@apiSaveBatteryLevel');

});

<?php

use App\Http\Controllers\APIControllers\AppPayloadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
 */

 Route::prefix('/v1')->group(function () {
    Route::get('/',[AppPayloadController::class,'Index']);
    Route::get('/cities', [AppPayloadController::class, 'GetCities']);
    Route::prefix('/admin')->group(function(){
        Route::post('/generate/api/user',[AppPayloadController::class,'GenerateApiUser']);
        Route::post('/store/cities', [AppPayloadController::class, 'StoreCities']);
        Route::post('/store/vehicle/type', [AppPayloadController::class, 'StoreVehicleType']);
    });
 });

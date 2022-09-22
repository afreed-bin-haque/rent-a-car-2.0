<?php

use App\Http\Controllers\BackendController\DashboardController;
use App\Http\Controllers\BackendController\AdminPanelController;
use App\Http\Controllers\BackendController\RiderPanelController;
use App\Http\Controllers\BackendController\UserController;
use App\Http\Controllers\FrontendController\PageItemController;
use App\Http\Controllers\FrontendController\StoreController;
use App\Http\Controllers\SystemAppPayloadController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RiderMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/', [DashboardController::class, 'DashboardRouting'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'DashboardRouting'])->name('/');
    Route::get('/fetch/rider/details',[DashboardController::class,'fetchRiderDetails'])->name('fetch.riderDetail');
    /**Internal Controller */
    Route::middleware([RiderMiddleware::class])->group(function(){
        Route::prefix('/rider/panel')->group(function(){
            Route::get('/view',[RiderPanelController::class,'ViewPanel'])->name('vehicle.registrationPanel');
            Route::post('/store/vehicle',[RiderPanelController::class,'StoreVehicle'])->name('store.vehicle');
        });
        Route::prefix('/rider/trip')->group(function(){
            Route::post('/store',[RiderPanelController::class,'PostTrip'])->name('post.trip');
            Route::get('/change/status',[RiderPanelController::class,'ChangeTripStatus'])->name('change.status');
             Route::get('/delete/trip/request',[RiderPanelController::class,'DeleteTrip'])->name('delete.tripRequestRider');
        });
    });
    Route::middleware([AdminMiddleware::class])->group(function(){
        Route::prefix('/admin/panel')->group(function(){
            Route::prefix('/vehicle')->group(function(){
                Route::get('/approval',[AdminPanelController::class,'VehicleApprovalPanel'])->name('vehicle.approvalPanel');
                Route::get('/view/{vehicle_id}',[AdminPanelController::class,'ViewVehicleDetails'])->name('vehicle.viewDetail');
                Route::get('/status/change/{vehicle_id}/{current_status}',[AdminPanelController::class,'VehicleSatusChange'])->name('vehicle.statusChange');
            });
            Route::prefix('/trip/post')->group(function(){
                Route::get('/view',[AdminPanelController::class,'ViewTravelPostApprovelPanel'])->name('post.approvalPanel');
                Route::get('/change/post/status/{post_id}/{current_status}',[AdminPanelController::class,'ChangePostStatus'])->name('change.postStatus');
                Route::get('/delete/post/{post_id}',[AdminPanelController::class,'DeletePost'])->name('delete.post');
            });
        });
    });
    Route::middleware([UserMiddleware::class])->group(function(){
        Route::prefix('/booking')->group(function(){
            Route::get('/view',[UserController::class,'ViewbookingPostDetails'])->name('view.postDetails');
            Route::post('/set',[UserController::class,'SetBooking'])->name('set.booking');
            Route::get('/fetch/user/status',[UserController::class,'FetchUSerStatus'])->name('fetch.userStatus');
            Route::get('/delete/trip/request',[UserController::class,'DeleteTrip'])->name('delete.tripRequest');
        });
    });
});
/**Dev routes */
Route::get('/register', [PageItemController::class, 'RegisterPageRoute'])->name('register');
Route::get('/register/rider', [PageItemController::class, 'RegisterRiderPageRoute'])->name('register.rider');
Route::post('/store/user', [StoreController::class, 'StoreUSer'])->name('store.user');
Route::post('/store/rider', [StoreController::class, 'StoreRider'])->name('store.rider');
//Log out
Route::get('/log/out', [DashboardController::class, 'Logout'])->name('logout');
/**Helping Routes */
Route::get('/fetch/to/city',[SystemAppPayloadController::class,'FetchFromCity'])->name('fetch.toCity');
Route::get('/fetch/car/main/iamge',[SystemAppPayloadController::class,'FetchCarMainImage'])->name('fetch.carMainImage');
Route::post('/start/rating',[UserController::class,'StoreRank'])->name('start.ratting');
Route::post('/no/rating',[UserController::class,'NoRatting'])->name('no.ratting');

<?php
/********************************* ROUTE CLASSESS ************************************************/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrdersController;
use Illuminate\Support\Facades\Route;

/********************************* WEB ROUTES ************************************************/


/********************************* REPROTS ************************************************/

//Payments
Route::get('app/google_spreedsheet', [OrdersController::class, 'index']);
Route::get('app/get_orders', [OrdersController::class, 'getOrders']);
Route::post('app/add_order', [OrdersController::class, 'addOrder']);
Route::get('app/generate_google_oauth_url', [AuthController::class, 'generateGoogleOAuthUrl']);
Route::post('app/get_google_oauth_code', [AuthController::class, 'getGoogleAuthorization']);
Route::get('app/get_access_token', [AuthController::class, 'getAccessToken']);


Route::get('/{any}', function() { return view('welcome'); })->where('any', '(.*)');

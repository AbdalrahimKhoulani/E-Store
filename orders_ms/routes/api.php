<?php

use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\OrderDetailsController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use App\Models\OrderDetails;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('checkCustomer')-> get('/test',function(){
// error_log('Hello from test');
// });


Route::prefix('/orders')
    ->middleware('checkCustomer')
    ->controller(OrderDetailsController::class)->group(function () {
        Route::post('/add-to-order', 'store');
        Route::put('/update-in-order/{id}', 'update');
        Route::delete('/delete-from-order/{id}', 'destroy');
    });


Route::middleware('checkCustomer')
    ->apiResource('/orders', OrderController::class);

Route::middleware('checkAdmin')
    ->resource('/products', ProductController::class);

Route::post('/users/store', [UserController::class, 'store']);

Route::middleware('checkCustomer')
    ->apiResource('/users', UserController::class);

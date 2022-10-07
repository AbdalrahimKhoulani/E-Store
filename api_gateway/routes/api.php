<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoriesController;
use App\Http\Controllers\API\OrderDetailsController;
use App\Http\Controllers\API\OrdersController;
use App\Http\Controllers\API\ProductsController;
use App\Http\Controllers\API\UserController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);

Route::prefix('/users')
    ->controller(UserController::class)
    ->group(function () {


        Route::put('/{id}/update', 'update');
        Route::delete('/{id}/delete', 'destroy');
        Route::get('/{id}/show', 'show');
        Route::get('/index', 'index');
    });

Route::apiResource('/categories',CategoriesController::class);

Route::apiResource('/products',ProductsController::class);

Route::controller(OrderDetailsController::class)->prefix('/orders')->group(function () {
    Route::post('/add-to-order', 'store');
    Route::put('/update-in-order/{id}', 'update');
    Route::delete('/delete-from-order/{id}', 'destroy');
});


Route::apiResource('/orders',OrdersController::class);

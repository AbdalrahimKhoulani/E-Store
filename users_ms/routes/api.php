<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CheckAuthorizeController;
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

Route::middleware('auth:sanctum')
    ->controller(CheckAuthorizeController::class)
    ->prefix('/users/check')
    ->group(function () {
        Route::middleware('checkAdmin')->get('/is-admin', 'isAdmin');
        Route::middleware('checkCustomer')->get('/is-customer', 'isCustomer');
    });

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/users')->controller(UserController::class)->group(function () {

        Route::middleware('checkAdmin')
            ->get('/index', 'index');

        Route::put('/{id}/update', 'update');

        Route::delete('/{id}/delete','destroy');

        Route::get('/{id}/show','show');

        Route::get('/get-authenticated-user','getAuthenticatedUser');

    });
});

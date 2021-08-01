<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TokoController;
use App\Http\Controllers\Api\UserControler;
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

Route::prefix('auth')->group(function () {
    Route::post('register', [UserControler::class, 'register']);
    Route::post('login', [UserControler::class, 'login']);
});

Route::prefix('toko')->group(function () {
    Route::get('/', [TokoController::class, 'showAll']);
    Route::get('/{user_id}/user', [TokoController::class, 'showAllByUser']);
    Route::get('/{id}/show', [TokoController::class, 'show']);
});


Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'showAll']);
    Route::get('/{toko_id}/toko', [ProductController::class, 'showAllByToko']);
    Route::get('/{id}/show', [ProductController::class, 'show']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('profile', [UserControler::class, 'profile']);
        Route::post('profile-update', [UserControler::class, 'profileUpdate']);
        Route::put('change-password', [UserControler::class, 'chengePassword']);
    });

    Route::prefix('toko')->group(function () {
        Route::post('/', [TokoController::class, 'store']);
        Route::post('{toko_id}/photo', [TokoController::class, 'storePhoto']);
        Route::put('{id}/update', [TokoController::class, 'update']);
    });

    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::post('{product_id}/photo', [ProductController::class, 'storePhoto']);
        Route::put('{id}/update', [ProductController::class, 'update']);
    });
});

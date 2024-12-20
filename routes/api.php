<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MenuController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Apply middleware to the group of authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/users', [UserController::class, 'users']);
        Route::get('/user/{userId}', [UserController::class, 'user']);
        Route::put('/user/profile/{userId}', [UserController::class, 'updateUserProfile']);

        Route::prefix('service')->group(function () {
            Route::post('/type', [ServiceController::class, 'insertServiceType']);
            Route::post('/postService', [ServiceController::class, 'insertService']);
            Route::get('/services', [ServiceController::class, 'getActiveServices']);
            Route::get('/services/{serviceId}', [ServiceController::class, 'getActiveService']);
            Route::put('/updateService/{serviceId}', [ServiceController::class, 'updateService']);
        });

        Route::prefix('menu')->group(function () {
            Route::post('/insertMenu', [MenuController::class, 'insertMenu']);
            Route::get('/menus', [MenuController::class, 'getActiveMenus']);
            Route::get('/menus/{menuId}', [MenuController::class, 'getActiveMenu']);
            Route::put('/updateMenu/{menuId}', [MenuController::class, 'updateMenu']);
        });
    });
});


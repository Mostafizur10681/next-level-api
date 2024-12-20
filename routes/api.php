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
        Route::post('/service/type', [ServiceController::class, 'insertServiceType']);
        Route::post('/service/postService', [ServiceController::class, 'insertService']);
        Route::post('/menu/postMenu', [MenuController::class, 'insertMenu']);
    });
});


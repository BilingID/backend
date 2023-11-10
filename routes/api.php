<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\IndexController;

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

Route::get('/', [IndexController::class, 'index']);
Route::post('/{id}', [IndexController::class, 'show']);

Route::prefix('v1/auth')->group(function () {
    Route::post('/google', [AuthController::class, 'loginWithGoogle']);
    Route::post('/google/register', [AuthController::class, 'registerWithGoogle']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::prefix('v1/users')->group(function () {
    // Route::prefix('users')->group(function() {
    Route::middleware('auth:sanctum')->group(function () {
        // Route::get('/', [UserController::class, 'index']);
        Route::get('/', [UserController::class, 'show']);
        Route::put('/', [UserController::class, 'update']);
        Route::put('/email', [AuthController::class, 'updateEmail']);
        Route::put('/password', [AuthController::class, 'updatePassword']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

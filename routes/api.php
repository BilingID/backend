<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

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

Route::get('/', function (){
    return response([
        'api' => env('APP_NAME', 'app'),
        'version' => env('APP_VERSION', '1.0.0'),
    ], 200);
});


Route::prefix('v1/users')->group(function() {
// Route::prefix('users')->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function() {
        Route::get('/', [UserController::class, 'index']);
        Route::put('/', [UserController::class, 'show']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/update', [UserController::class, 'update']);
        Route::post('/email', [AuthController::class, 'updateEmail']);
        Route::post('/password', [AuthController::class, 'updatePassword']);
    });
});

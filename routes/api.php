<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\IndexController;
use App\Http\Controllers\Api\PsychologController;
use App\Http\Controllers\Api\PsychotestController;
use App\Http\Controllers\Api\CounselingController;

use App\Http\Controllers\QR\QrCodeController;
use App\Mail\SendEmail;
use Google\Service\Adsense\Row;

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

Route::prefix('v1')->group(function () {
    Route::get('statistic', [IndexController::class, 'statistic']);

    Route::prefix('auth')->group(function () {
        Route::post('/google', [AuthController::class, 'loginWithGoogle']);
        Route::post('/google/register', [AuthController::class, 'registerWithGoogle']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/forgot-password', [AuthController::class, 'forgottenPassword']);
    });

    Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::prefix('users')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'update']);
        // Route::get('/', [UserController::class, 'getUsersWithRole']);
        Route::put('/password', [AuthController::class, 'updatePassword']);
        // Route::get('/', [UserController::class, 'index']);
        // Route::put('/email', [AuthController::class, 'updateEmail']); // remove unused feature
        Route::get('/psychologist', [PsychologController::class, 'index']);
        Route::put('/psychologist/update', [PsychologController::class, 'update']);
        Route::get('/psychologist/{id}', [PsychologController::class, 'show']);
    });


    Route::prefix('psikotes')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [PsychotestController::class, 'index']);
        Route::post('/', [PsychotestController::class, 'store']);
        Route::get('/{code}', [PsychotestController::class, 'getPayment']);
        Route::get('/{code}/questions', [PsychotestController::class, 'getQuestions']);
        Route::post('/{code}/answer', [PsychotestController::class, 'storeAnswer']);
        Route::get('/{code}/result', [PsychotestController::class, 'getResult']);
        Route::post('/{code}/result', [PsychotestController::class, 'updateResult']);
    });

    Route::prefix('konseling')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [CounselingController::class, 'index']);
        Route::get('/{id}/info', [CounselingController::class, 'getMeetInfo']);
        Route::post('/', [CounselingController::class, 'store']);
        Route::get('/{id}', [CounselingController::class, 'getPayment']);
        Route::post('/{id}/update', [CounselingController::class, 'update']);
        Route::get('/{id}/result', [CounselingController::class, 'getResult']);
    });

    Route::prefix('konseling')->group(function () {
        Route::get('/{id}/process', [CounselingController::class, 'processPayment']); // SIMULATE PAYMENT PROCESS
        Route::get('/{id}/done', [CounselingController::class, 'updateStatus']); // SIMULATE PAYMENT PROCESS
    });

    Route::prefix('psikotes')->group(function () {
        Route::get('/{code}/process', [PsychotestController::class, 'processPayment']); // SIMULATE PAYMENT PROCESS
    });
});

Route::prefix('test')->group(function () {
    Route::get('/qr/{data}', [QrCodeController::class, 'generate']);
    Route::get('/sendemail', function () {
        $data = [
            'name' => 'Abdullah',
            'body' => 'Testing Kirim Email di Santri Koding'
        ];

        Mail::to('crdua2@gmail.com')->send(new SendEmail($data));

        dd("Email Berhasil dikirim.");
    });
});

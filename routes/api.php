<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::get('/profile', [ProfileController::class, 'show']);

    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
    Route::get('/chat/history', [ChatController::class, 'chatHistory']);

    Route::post('/status/update', [StatusController::class, 'updateStatus']);
    Route::get('/status/view', [StatusController::class, 'viewStatus']);

    Route::post('/chat/self-destruct', [ChatController::class, 'selfDestructMessage']);
    Route::post('/profile/ghost-mode', [ProfileController::class, 'toggleGhostMode']);
});
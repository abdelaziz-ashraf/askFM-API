<?php

use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Middleware\EnsureEmailIsVerified;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('email/verify-code', [AuthController::class, 'verifyCode']);
});

Route::middleware(['auth:sanctum', EnsureEmailIsVerified::class])->group(function () {

    Route::prefix('questions')->group(function () {
        Route::get('/', [QuestionController::class, 'index']); // Get recommended questions
        Route::post('/{receiver}', [QuestionController::class, 'store']);
        Route::delete('/{question}', [QuestionController::class, 'destroy']);
        Route::patch('/{question}/toggle-like', [QuestionController::class, 'toggleLike']);
    });

    Route::prefix('answers')->group(function () {
        Route::get('/{user}', [AnswerController::class, 'index']); // Get user answers
        Route::post('/{question}', [AnswerController::class, 'store']); // Answer a question
        Route::delete('/{question}', [AnswerController::class, 'destroy']);
    });
});


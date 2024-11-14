<?php

use App\Http\Controllers\Api\AnswerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\QuestionController;

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('/email/verify-code', [AuthController::class, 'verifyCode']);

Route::post('/questions', [QuestionController::class, 'store'])
    ->middleware('auth:sanctum');

Route::get('/questions', [QuestionController::class, 'index']);

Route::post('/answers/{question_id}', [AnswerController::class, 'store'])
    ->middleware('auth:sanctum');

Route::get('/answers/{question_id}', [AnswerController::class, 'index']);
Route::get('/answers/{user}', [AnswerController::class, 'userAnswers']);
Route::post('/answers/{answer_id}/toggle-like', [AnswerController::class, 'toggleLike'])
    ->middleware('auth:sanctum');

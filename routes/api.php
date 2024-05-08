<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication\AuthController;

Route::post('signup', [AuthController::class, 'registerHandler']);
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmailHandler'])->name('verify.email');
Route::post('/login', [AuthController::class, 'loginHandler']);
Route::post('forgot-password', [AuthController::class, 'forgotPasswordHandler']);
Route::get('password-reset/{token}', [AuthController::class, 'resetPasswordHandler'])->name('reset.password');



Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logoutHandler']);
});


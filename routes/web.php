<?php

use Illuminate\Support\Facades\Route;
use Laratribe\NativephpAuth\Http\Controllers\ForgotPasswordController;
use Laratribe\NativephpAuth\Http\Controllers\LoginController;
use Laratribe\NativephpAuth\Http\Controllers\OtpController;
use Laratribe\NativephpAuth\Http\Controllers\RegisterController;
use Laratribe\NativephpAuth\Http\Controllers\ResetPasswordController;

Route::prefix(config('nativephp-auth.route_prefix', 'auth'))
    ->middleware(config('nativephp-auth.middleware', ['web']))
    ->name(config('nativephp-auth.route_name_prefix', 'nativephp-auth.'))
    ->group(function () {

        // Login
        Route::get('login', [LoginController::class, 'show'])->name('login.show');
        Route::post('login', [LoginController::class, 'login'])->name('login');
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        // Register
        Route::get('register', [RegisterController::class, 'show'])->name('register.show');
        Route::post('register', [RegisterController::class, 'register'])->name('register');

        // OTP verify (shared by register + reset-password flows)
        Route::get('verify-otp', [OtpController::class, 'show'])->name('otp.show');
        Route::post('verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
        Route::post('verify-otp/resend', [OtpController::class, 'resend'])->name('otp.resend');

        // Forgot password
        Route::get('forgot-password', [ForgotPasswordController::class, 'show'])->name('password.forgot');
        Route::post('forgot-password', [ForgotPasswordController::class, 'send'])->name('password.forgot.send');

        // Reset password
        Route::get('reset-password', [ResetPasswordController::class, 'show'])->name('password.reset.show');
        Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');
    });

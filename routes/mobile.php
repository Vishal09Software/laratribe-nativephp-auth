<?php

use Illuminate\Support\Facades\Route;
use Laratribe\NativephpAuth\Native\ForgotPasswordScreen;
use Laratribe\NativephpAuth\Native\LoginScreen;
use Laratribe\NativephpAuth\Native\OtpScreen;
use Laratribe\NativephpAuth\Native\RegisterScreen;
use Laratribe\NativephpAuth\Native\ResetPasswordScreen;

// This file is only ever loaded by the service provider when
// Native\Mobile\Edge\NativeComponent exists — i.e. nativephp/mobile ^4.0
// is installed — so Route::native() is guaranteed to be available here.
$prefix = rtrim(config('nativephp-auth.native.route_prefix', '/auth-native'), '/');

Route::native("{$prefix}/login", LoginScreen::class)
    ->name('nativephp-auth.native.login');

Route::native("{$prefix}/register", RegisterScreen::class)
    ->name('nativephp-auth.native.register');

Route::native("{$prefix}/verify-otp", OtpScreen::class)
    ->name('nativephp-auth.native.otp');

Route::native("{$prefix}/forgot-password", ForgotPasswordScreen::class)
    ->name('nativephp-auth.native.forgot-password');

Route::native("{$prefix}/reset-password", ResetPasswordScreen::class)
    ->name('nativephp-auth.native.reset-password');

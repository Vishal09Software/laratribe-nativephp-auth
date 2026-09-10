<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route Settings
    |--------------------------------------------------------------------------
    | These control the URL prefix and route name prefix used by every
    | route this package registers (login, register, otp, password reset).
    | The same routes/views are used whether the app is opened on Android,
    | iOS (inside the NativePHP webview) or on the Web — one codebase.
    */
    'route_prefix' => env('NATIVEPHP_AUTH_ROUTE_PREFIX', 'auth'),
    'route_name_prefix' => 'nativephp-auth.',
    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Redirects
    |--------------------------------------------------------------------------
    */
    'redirects' => [
        'after_login' => '/home',
        'after_register_verified' => '/home',

        // Left null on purpose: LoginController/ResetPasswordController
        // fall back to route('nativephp-auth.login.show') when these are
        // null, so the redirect always matches wherever route_prefix above
        // actually put the login page. A hardcoded 'auth/login' here would
        // silently 404 the moment someone changes route_prefix.
        'after_logout' => null,
        'after_password_reset' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Settings
    |--------------------------------------------------------------------------
    */
    'otp' => [
        'length' => env('NATIVEPHP_AUTH_OTP_LENGTH', 6),
        'expires_in_minutes' => env('NATIVEPHP_AUTH_OTP_EXPIRES_IN_MINUTES', 10),
        'channel' => env('NATIVEPHP_AUTH_OTP_CHANNEL', 'mail'), // mail | log (log is useful for local/dev testing)
        'throttle_seconds' => env('NATIVEPHP_AUTH_OTP_THROTTLE_SECONDS', 60), // minimum gap before a new OTP can be resent
    ],

    /*
    |--------------------------------------------------------------------------
    | Require Email Verification Before Login
    |--------------------------------------------------------------------------
    */
    'require_verified_email' => true,

    /*
    |--------------------------------------------------------------------------
    | NativePHP v4 / SuperNative (EDGE) Screens
    |--------------------------------------------------------------------------
    | Settings for the true EDGE-component version of this module, i.e. the
    | screens under src/Native/*Screen.php + resources/views/native/*.blade.php,
    | registered via Route::native(). These render as real SwiftUI/Jetpack
    | Compose UI — no web view. This is separate from route_prefix/redirects
    | above, which only apply to the plain-HTML web-view auth module.
    |
    | These routes are only registered when nativephp/mobile v4's
    | Native\Mobile\Edge\NativeComponent class is present, so installing
    | this package never breaks an app that isn't using NativePHP Mobile.
    */
    'native' => [
        'route_prefix' => env('NATIVEPHP_AUTH_NATIVE_ROUTE_PREFIX', '/auth-native'),

        'redirects' => [
            // Where a successful login/verified-registration lands.
            'after_login' => '/home',
            'after_register_verified' => '/home',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Branding — Colors
    |--------------------------------------------------------------------------
    | All auth screens (Login, Register, OTP, Forgot/Reset Password) read
    | their theme from here so you can re-skin the whole module by only
    | editing this file — no blade editing required.
    */
    'colors' => [
        'primary'      => env('NATIVEPHP_AUTH_COLOR_PRIMARY', '#6C5CE7'),
        'primary_dark' => env('NATIVEPHP_AUTH_COLOR_PRIMARY_DARK', '#5646c9'),
        'secondary'    => env('NATIVEPHP_AUTH_COLOR_SECONDARY', '#00B894'),
        'background'   => env('NATIVEPHP_AUTH_COLOR_BACKGROUND', '#F7F7FB'),
        'card'         => env('NATIVEPHP_AUTH_COLOR_CARD', '#FFFFFF'),
        'text'         => env('NATIVEPHP_AUTH_COLOR_TEXT', '#1A1A2E'),
        'muted_text'   => env('NATIVEPHP_AUTH_COLOR_MUTED_TEXT', '#7A7A8C'),
        'error'        => env('NATIVEPHP_AUTH_COLOR_ERROR', '#E63946'),
        'success'      => env('NATIVEPHP_AUTH_COLOR_SUCCESS', '#2ECC71'),
        'input_border' => env('NATIVEPHP_AUTH_COLOR_INPUT_BORDER', '#E0E0EB'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Branding — Logo / Icon
    |--------------------------------------------------------------------------
    | Run: php artisan vendor:publish --tag=nativephp-auth-assets
    | then point this to your own file inside /public, e.g. 'images/logo.png'.
    */
    'branding' => [
        'app_name' => env('APP_NAME', 'My App'),
        'logo'     => env('NATIVEPHP_AUTH_LOGO', 'vendor/nativephp-auth/icon-placeholder.svg'),
        'favicon'  => env('NATIVEPHP_AUTH_FAVICON', 'vendor/nativephp-auth/icon-placeholder.svg'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Text / Labels
    |--------------------------------------------------------------------------
    | Every user-facing string in the auth module. Override any of these
    | (in your own published config) to change copy or translate the
    | module without touching any blade files.
    */
    'text' => [
        'login' => [
            'title'       => 'Welcome back',
            'subtitle'    => 'Log in to continue',
            'email_label' => 'Email address',
            'password_label' => 'Password',
            'submit'      => 'Log In',
            'forgot_link' => 'Forgot password?',
            'register_prompt' => "Don't have an account?",
            'register_link'   => 'Create one',
        ],
        'register' => [
            'title'       => 'Create your account',
            'subtitle'    => 'It only takes a minute',
            'name_label'  => 'Full name',
            'email_label' => 'Email address',
            'password_label' => 'Password',
            'confirm_password_label' => 'Confirm password',
            'submit'      => 'Create Account',
            'login_prompt' => 'Already have an account?',
            'login_link'   => 'Log in',
        ],
        'otp' => [
            'title'    => 'Verify your account',
            'subtitle' => 'Enter the code we sent to :identifier',
            'code_label' => 'Verification code',
            'submit'   => 'Verify',
            'resend_prompt' => "Didn't get a code?",
            'resend_link'   => 'Resend code',
            'resent_message' => 'A new code has been sent.',
        ],
        'forgot_password' => [
            'title'    => 'Reset your password',
            'subtitle' => 'Enter your email and we will send you a code',
            'email_label' => 'Email address',
            'submit'   => 'Send Code',
            'back_to_login' => 'Back to login',
        ],
        'reset_password' => [
            'title'    => 'Set a new password',
            'subtitle' => 'Choose a new password for your account',
            'password_label' => 'New password',
            'confirm_password_label' => 'Confirm new password',
            'submit'   => 'Update Password',
        ],
    ],
];

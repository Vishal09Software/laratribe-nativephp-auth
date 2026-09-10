<?php

namespace Laratribe\NativephpAuth;

use Illuminate\Support\ServiceProvider;

class NativephpAuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nativephp-auth.php', 'nativephp-auth');
    }

    public function boot(): void
    {
        // Web-view routes (always safe: only needs Laravel itself)
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // EDGE / SuperNative routes (routes/mobile.php calls Route::native(),
        // which only exists once nativephp/mobile ^4.0 is installed). Guard
        // this behind a class_exists() check so installing this package
        // never breaks an app that isn't using NativePHP Mobile v4 — the
        // web-view auth module above keeps working on its own either way.
        if (class_exists(\Native\Mobile\Edge\NativeComponent::class)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/mobile.php');
        }

        // Views — accessible as view('nativephp-auth::auth.login'),
        // view('nativephp-auth::native.login'), etc.
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nativephp-auth');

        // Migrations (otps table + optional users.email_verified_at)
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            // Config
            $this->publishes([
                __DIR__.'/../config/nativephp-auth.php' => config_path('nativephp-auth.php'),
            ], 'nativephp-auth-config');

            // Views (so devs can fully override markup/blade files)
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/nativephp-auth'),
            ], 'nativephp-auth-views');

            // Public assets (icon/logo placeholder)
            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/nativephp-auth'),
            ], 'nativephp-auth-assets');

            // Convenience: publish everything at once
            $this->publishes([
                __DIR__.'/../config/nativephp-auth.php' => config_path('nativephp-auth.php'),
                __DIR__.'/../resources/views' => resource_path('views/vendor/nativephp-auth'),
                __DIR__.'/../resources/assets' => public_path('vendor/nativephp-auth'),
            ], 'nativephp-auth');
        }
    }
}

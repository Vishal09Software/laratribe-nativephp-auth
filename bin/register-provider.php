<?php

/**
 * Laratribe NativePHP Auth — manual provider registration fallback.
 *
 * You almost never need this. Laravel's package auto-discovery (the
 * "extra.laravel.providers" block in this package's composer.json)
 * registers NativephpAuthServiceProvider automatically the moment you
 * `composer require` it. Run this script only if:
 *
 *   php artisan route:list --name=nativephp-auth
 *
 * comes back empty after installing — which usually means auto-discovery
 * is disabled for this package in your app's own composer.json (an
 * "extra.laravel.dont-discover" entry).
 *
 * Usage (from your Laravel app's root directory):
 *   php bin/register-provider.php
 *
 * Safe to run more than once — it checks whether the provider is already
 * registered before writing anything, and never touches any file other
 * than bootstrap/providers.php (Laravel 11/12) or config/app.php
 * (Laravel 10).
 */

const PROVIDER_FQCN = 'Laratribe\\NativephpAuth\\NativephpAuthServiceProvider';

function fail(string $message): never
{
    fwrite(STDERR, "✗ {$message}\n");
    exit(1);
}

function ok(string $message): void
{
    echo "✓ {$message}\n";
}

// Must be run from a Laravel app root.
if (! is_dir('bootstrap') && ! is_file('config/app.php')) {
    fail('This does not look like a Laravel app root (no bootstrap/ or config/app.php found). '
        .'Run this script from your Laravel application\'s root directory.');
}

$providersFile = 'bootstrap/providers.php';
$appConfigFile = 'config/app.php';

if (is_file($providersFile)) {
    // Laravel 11 / 12
    $contents = file_get_contents($providersFile);

    if ($contents === false) {
        fail("Could not read {$providersFile}.");
    }

    if (str_contains($contents, PROVIDER_FQCN)) {
        ok("Already registered in {$providersFile} — nothing to do.");
        exit(0);
    }

    $line = '    '.PROVIDER_FQCN.'::class,';

    $updated = preg_replace('/return \[/', "return [\n{$line}", $contents, 1, $count);

    if ($count !== 1 || $updated === null) {
        fail("Could not find a \"return [\" line to insert into inside {$providersFile}. ".
            'Please add the provider manually — see the README.');
    }

    if (file_put_contents($providersFile, $updated) === false) {
        fail("Found the insertion point but could not write to {$providersFile}. Check file permissions.");
    }

    ok("Added {$line} to {$providersFile}");
    exit(0);
}

if (is_file($appConfigFile)) {
    // Laravel 10 and earlier: config/app.php's 'providers' array/merge().
    $contents = file_get_contents($appConfigFile);

    if ($contents === false) {
        fail("Could not read {$appConfigFile}.");
    }

    if (str_contains($contents, PROVIDER_FQCN)) {
        ok("Already registered in {$appConfigFile} — nothing to do.");
        exit(0);
    }

    // Insert directly above the first App\Providers\...::class entry,
    // which exists in every stock Laravel app regardless of whether
    // 'providers' is a plain array or a ServiceProvider::defaultProviders()
    // ->merge([...]) call — both forms include this line.
    $anchorPattern = '/( *)(App\\\\Providers\\\\[A-Za-z]+ServiceProvider::class,)/';

    $updated = preg_replace_callback($anchorPattern, function (array $m) {
        return $m[1].PROVIDER_FQCN."::class,\n".$m[1].$m[2];
    }, $contents, 1, $count);

    if ($count !== 1 || $updated === null) {
        fail("Could not find an App\\Providers\\...ServiceProvider::class line to insert above in ".
            "{$appConfigFile}. Please add the provider manually — see the README.");
    }

    if (file_put_contents($appConfigFile, $updated) === false) {
        fail("Found the insertion point but could not write to {$appConfigFile}. Check file permissions.");
    }

    ok('Added '.PROVIDER_FQCN."::class to {$appConfigFile}");
    exit(0);
}

fail('Could not find bootstrap/providers.php or config/app.php. '
    .'Run this script from your Laravel application\'s root directory.');

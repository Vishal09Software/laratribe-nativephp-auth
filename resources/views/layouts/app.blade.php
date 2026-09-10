<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    {{-- Viewport tuned so the same markup looks right in the NativePHP
         webview on Android/iOS as well as in a desktop browser. --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
    <title>{{ $title ?? config('nativephp-auth.branding.app_name', config('app.name')) }}</title>
    <link rel="icon" href="{{ asset(config('nativephp-auth.branding.favicon')) }}">
    <style>
        :root {
            --nap-primary: {{ config('nativephp-auth.colors.primary') }};
            --nap-primary-dark: {{ config('nativephp-auth.colors.primary_dark') }};
            --nap-secondary: {{ config('nativephp-auth.colors.secondary') }};
            --nap-bg: {{ config('nativephp-auth.colors.background') }};
            --nap-card: {{ config('nativephp-auth.colors.card') }};
            --nap-text: {{ config('nativephp-auth.colors.text') }};
            --nap-muted: {{ config('nativephp-auth.colors.muted_text') }};
            --nap-error: {{ config('nativephp-auth.colors.error') }};
            --nap-success: {{ config('nativephp-auth.colors.success') }};
            --nap-border: {{ config('nativephp-auth.colors.input_border') }};
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--nap-bg);
            color: var(--nap-text);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            padding: 24px;
            padding-top: calc(24px + env(safe-area-inset-top));
            padding-bottom: calc(24px + env(safe-area-inset-bottom));
        }
        .nap-card {
            width: 100%;
            max-width: 400px;
            background: var(--nap-card);
            border-radius: 20px;
            padding: 32px 28px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .nap-logo {
            display: block;
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            border-radius: 16px;
        }
        .nap-title {
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            margin: 0 0 4px;
            color: var(--nap-text);
        }
        .nap-subtitle {
            font-size: 14px;
            text-align: center;
            color: var(--nap-muted);
            margin: 0 0 24px;
        }
        .nap-field { margin-bottom: 16px; }
        .nap-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--nap-text);
        }
        .nap-input {
            width: 100%;
            padding: 13px 14px;
            border-radius: 12px;
            border: 1.5px solid var(--nap-border);
            font-size: 15px;
            background: var(--nap-card);
            color: var(--nap-text);
            outline: none;
            transition: border-color .15s ease;
        }
        .nap-input:focus { border-color: var(--nap-primary); }
        .nap-error {
            color: var(--nap-error);
            font-size: 12.5px;
            margin-top: 6px;
        }
        .nap-status {
            /* Plain rgba instead of color-mix(): NativePHP screens render
               inside the device's system webview (Android WebView /
               WKWebView), which on older OS versions can lag behind
               desktop Chrome/Safari and not support color-mix() yet. */
            background: rgba(46, 204, 113, 0.12);
            color: var(--nap-success);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13.5px;
            margin-bottom: 18px;
            text-align: center;
        }
        .nap-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: var(--nap-primary);
            color: #fff;
            font-size: 15.5px;
            font-weight: 700;
            cursor: pointer;
            transition: background .15s ease;
        }
        .nap-btn:hover { background: var(--nap-primary-dark); }
        .nap-btn:active { transform: scale(0.99); }
        .nap-link {
            color: var(--nap-primary);
            text-decoration: none;
            font-weight: 600;
        }
        .nap-link:hover { text-decoration: underline; }
        .nap-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 13.5px;
            color: var(--nap-muted);
        }
        .nap-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: -6px;
            margin-bottom: 18px;
            font-size: 13px;
        }
        .nap-otp-input {
            letter-spacing: 8px;
            text-align: center;
            font-size: 22px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="nap-card">
        <img class="nap-logo" src="{{ asset(config('nativephp-auth.branding.logo')) }}" alt="{{ config('nativephp-auth.branding.app_name') }}">
        @yield('content')
    </div>
</body>
</html>

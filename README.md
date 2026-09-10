# Laratribe NativePHP Auth

A drop-in **Auth module** for [NativePHP](https://nativephp.com) apps: Login, Register,
OTP Verify, Forgot Password, Reset Password.

## Which version do I need?

NativePHP v4 has no single rendering mode that covers Web, iOS, *and* Android at once —
browsers render HTML, and v4's SuperNative engine renders iOS/Android as real native
SwiftUI/Compose views instead of HTML. So "one code, three platforms" means **one shared
backend, two UI layers**, and this package gives you both:

| | `resources/views/auth/*.blade.php` (**Web-view module**) | `src/Native/*Screen.php` (**EDGE module**) |
|---|---|---|
| Renders as | Plain HTML/CSS | Real **SwiftUI** (iOS) / **Jetpack Compose** (Android) |
| Runs on | Your normal Laravel web app, or inside a NativePHP `<webview>` component | NativePHP v4's **SuperNative** engine — no web view involved |
| Platforms covered | **Web**, plus Android/iOS if you embed it in a `<webview>` | **Android + iOS** natively |
| Requires | Just Laravel | `nativephp/mobile` ^4.0 |

Both share the same `OtpService`, `otps` table, config, and text/color settings — only the
UI layer differs, because that's genuinely how the platforms work. In practice: use the
**web-view module** for your browser-based app, and the **EDGE module** for your iOS/Android
app — same Laravel backend, same OTP logic, same `users` table, two front ends.

- ✅ Login
- ✅ Register
- ✅ OTP Verify (used by both registration and password reset)
- ✅ Forgot Password
- ✅ New / Reset Password
- 🎨 Fully configurable **colors**, **logo/icon**, and **text**
- 📦 One `composer require`, one `vendor:publish`, done

---

## Quick start

### A) Web app only

```bash
composer require laratribe/nativephp-auth:@dev   # see "Installing this package" below
php artisan vendor:publish --tag=nativephp-auth
php artisan migrate
php artisan serve
```

Visit `http://127.0.0.1:8000/auth/login`. That's it — plain Laravel, nothing else to set up.

### B) iOS + Android (NativePHP v4 EDGE)

```bash
# 1. Start from a fresh Laravel app, then install NativePHP Mobile v4
composer require nativephp/mobile
php artisan native:install          # prompts for android / ios / both

# 2. Install this package (see "Installing this package" below for the
#    local-path step — it's not on Packagist)
composer require laratribe/nativephp-auth:@dev
php artisan vendor:publish --tag=nativephp-auth
php artisan migrate

# 3. Point your app at the native login screen
echo 'NATIVEPHP_START_URL=/auth-native/login' >> .env

# 4. Build and launch on a connected device/simulator
php artisan native:run          # then pick ios or android when prompted
```

Useful follow-ups while you iterate:

```bash
php artisan native:run --watch      # hot-reload UI changes as you edit Blade
php artisan native:open ios         # open the Xcode project directly
php artisan native:open android     # open the Android Studio project directly
php artisan native:tail             # tail Laravel logs from a running Android app
```

### C) All three from one Laravel app

Do both A and B in the same app — the `otps` table, `OtpService`, config, and `users`
table are shared automatically. Ship the web-view routes for your browser users and the
EDGE routes for your mobile app; they don't conflict.

---

## Installing this package

This package isn't published on Packagist — it comes to you as a folder, so Composer
needs to be told where to find it via a **path repository**.

**1. Unzip it into your Laravel app:**
```
your-laravel-app/
  packages/
    laratribe/
      nativephp-auth/        ← contents of this package go here
```

**2. Add a `repositories` entry to your app's own `composer.json`** (top-level key,
alongside `require`):
```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/laratribe/nativephp-auth",
            "options": { "symlink": true }
        }
    ]
}
```

**3. Require it, allowing dev stability** (a path package has no tagged release):
```bash
composer require laratribe/nativephp-auth:@dev
```
If Composer complains about stability, also add to your app's `composer.json`:
```json
"minimum-stability": "dev",
"prefer-stable": true
```

**4. If a class isn't found after installing, regenerate the autoloader:**
```bash
composer dump-autoload
```

---

## Web-view module reference

Once installed (see Quick start A above), these routes are live:

| Route name | Method | Path |
|---|---|---|
| `nativephp-auth.login.show` | GET | `/auth/login` |
| `nativephp-auth.login` | POST | `/auth/login` |
| `nativephp-auth.logout` | POST | `/auth/logout` |
| `nativephp-auth.register.show` | GET | `/auth/register` |
| `nativephp-auth.register` | POST | `/auth/register` |
| `nativephp-auth.otp.show` | GET | `/auth/verify-otp?type=register\|reset-password` |
| `nativephp-auth.otp.verify` | POST | `/auth/verify-otp` |
| `nativephp-auth.otp.resend` | POST | `/auth/verify-otp/resend` |
| `nativephp-auth.password.forgot` | GET | `/auth/forgot-password` |
| `nativephp-auth.password.forgot.send` | POST | `/auth/forgot-password` |
| `nativephp-auth.password.reset.show` | GET | `/auth/reset-password` |
| `nativephp-auth.password.reset` | POST | `/auth/reset-password` |

Change the `/auth` prefix via `NATIVEPHP_AUTH_ROUTE_PREFIX` in `.env`, or
`route_prefix` in the published config.

If you're still on **NativePHP v3** (web-view-only architecture), point your
NativePHP window at these routes directly — same HTML renders in the web
view on Android/iOS as it does in a normal browser, no extra code needed.

---

## Customize colors

Edit `config/nativephp-auth.php` (after publishing) or set env vars — no
blade editing needed:

```env
NATIVEPHP_AUTH_COLOR_PRIMARY=#FF6B00
NATIVEPHP_AUTH_COLOR_PRIMARY_DARK=#CC5500
NATIVEPHP_AUTH_COLOR_SECONDARY=#00B894
NATIVEPHP_AUTH_COLOR_BACKGROUND=#0F0F1A
NATIVEPHP_AUTH_COLOR_CARD=#1A1A2E
NATIVEPHP_AUTH_COLOR_TEXT=#FFFFFF
```

## Customize the icon / logo

```bash
php artisan vendor:publish --tag=nativephp-auth-assets
```

Then drop your own file into `public/images/logo.png` and point to it:

```env
NATIVEPHP_AUTH_LOGO=images/logo.png
NATIVEPHP_AUTH_FAVICON=images/favicon.png
```

## Customize text / copy

Every label lives in `config('nativephp-auth.text')`. Override any of it,
e.g. in your published config:

```php
'text' => [
    'login' => [
        'title' => 'Hey, welcome back 👋',
        'submit' => 'Sign In',
        // ...
    ],
],
```

---

## How it works

- **Register** → creates the user (unverified) → sends a 6‑digit OTP by email
  → redirects to the shared OTP screen.
- **Verify OTP** (`type=register`) → marks the user verified, logs them in,
  redirects to `redirects.after_register_verified`.
- **Forgot Password** → sends an OTP (`type=reset-password`) to the shared
  OTP screen.
- **Verify OTP** (`type=reset-password`) → marks the OTP verified and opens
  the **Reset Password** screen (gated: you can't reach it without a
  verified OTP for that email).
- **Reset Password** → updates the password and redirects to login.

All OTPs live in a dedicated `otps` table (not on the `users` table), are
single-use, expire after `otp.expires_in_minutes` (default 10), and are
throttled by `otp.throttle_seconds` (default 60) to prevent spamming resend.

Set `NATIVEPHP_AUTH_OTP_CHANNEL=log` (via `otp.channel` in the config) during
local development to have codes written to your log file instead of sent by
email.

---

## Using the EDGE / SuperNative version (NativePHP v4)

See **Quick start B** above for the install steps. Once installed, the
package registers five native screens (real SwiftUI/Compose UI, not HTML) at:

| Route name | Path |
|---|---|
| `nativephp-auth.native.login` | `/auth-native/login` |
| `nativephp-auth.native.register` | `/auth-native/register` |
| `nativephp-auth.native.otp` | `/auth-native/verify-otp` |
| `nativephp-auth.native.forgot-password` | `/auth-native/forgot-password` |
| `nativephp-auth.native.reset-password` | `/auth-native/reset-password` |

Already set your `NATIVEPHP_START_URL` per Quick start B? You're done. To
navigate to the login screen from elsewhere in your own native components:

```php
$this->navigate($this->route('nativephp-auth.native.login'));
```

The screens are `Laratribe\NativephpAuth\Native\{Login,Register,Otp,ForgotPassword,ResetPassword}Screen`,
each a `NativeComponent` (Livewire-shaped: public properties are state, action methods
like `login()`/`register()`/`verify()` are called from `@press`, and `render()` returns
the matching Blade view under `resources/views/native/`).

**Colors on the EDGE screens.** The views use NativePHP's own semantic theme classes
(`bg-theme-background`, `text-theme-on-background`, `text-theme-primary`,
`text-theme-destructive`, etc.) rather than this package's `config('nativephp-auth.colors.*)`
hex values — those hex values only drive the web-view screens' CSS. To theme the native
screens, adjust NativePHP's own theme configuration (see NativePHP's
[Theming](https://nativephp.com/docs/mobile/4/digging-deeper/theming) docs) so your
app-wide `primary` / `destructive` / `background` tokens match your brand; the EDGE
screens will pick that up automatically, same as every other native screen in your app.

**A note on authentication in a native, on-device app.** NativePHP's own docs point out
that a NativePHP Mobile app runs your whole Laravel app *on the device* — there's no
server round-trip — so the usual assumption of "session cookie = trusted server session"
doesn't carry over the same way it does for a web app talking to a remote backend. This
package's `Auth::attempt()` / `Auth::login()` calls work correctly for **local,
on-device accounts** (the `users` table lives in the app's on-device SQLite database,
same as everything else). If your app also needs to authenticate against a *remote* API
(e.g. to sync data to your server), NativePHP's docs recommend a token-based approach
(Sanctum or OAuth) on top of — not instead of — this local sign-in, since a local session
alone doesn't prove anything to a server that never saw it. See NativePHP's
[Authentication](https://nativephp.com/docs/mobile/4/digging-deeper/authentication) docs
if that applies to you.

**Prop names may drift.** The EDGE component API (`native:outlined-text-input`,
`native:button`, etc.) is still in beta as of this package's release and documented as
subject to change before NativePHP v4's stable release. If a prop like `native:button`'s
`:loading` binding throws an "unknown attribute" error on your installed version, check
that component's page under `https://nativephp.com/docs/mobile/4/edge-components/` and
adjust the Blade views in `resources/views/native/` accordingly — they're plain published
files once you run `vendor:publish --tag=nativephp-auth-views`.

## Fully overriding the views

If you want full control over the markup, publish the views and edit them
directly — the package will automatically prefer your published copies:

```bash
php artisan vendor:publish --tag=nativephp-auth-views
```

Files land in `resources/views/vendor/nativephp-auth/`.

---

## Package info

- **Package name:** `laratribe/nativephp-auth`
- **Namespace:** `Laratribe\NativephpAuth`
- **Requires:** PHP ^8.1, Laravel ^10 / ^11 / ^12
- **Optional:** `nativephp/mobile` ^4.0 for the EDGE/SuperNative screens
- **License:** MIT

<?php

namespace Laratribe\NativephpAuth\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laratribe\NativephpAuth\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('nativephp-auth::auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->onlyInput('email');
        }

        $user = Auth::user();

        // Checked directly against the email_verified_at column rather than
        // Laravel's MustVerifyEmail::hasVerifiedEmail(), so verification is
        // enforced even if the app's User model doesn't implement that
        // interface (this package only ever needs the column, which our
        // own migration guarantees exists).
        if (config('nativephp-auth.require_verified_email', true)
            && is_null($user->email_verified_at)
        ) {
            Auth::logout();

            session(['nativephp_auth.pending_identifier' => $user->email]);

            return redirect()
                ->route('nativephp-auth.otp.show', ['type' => 'register'])
                ->with('status', 'Please verify your email before logging in.');
        }

        $request->session()->regenerate();

        return redirect()->intended(config('nativephp-auth.redirects.after_login', '/home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // config value wins if the developer set one; otherwise resolve the
        // login page by its route name so this always matches route_prefix,
        // even if that config was changed from the default 'auth'.
        return redirect(
            config('nativephp-auth.redirects.after_logout') ?? route('nativephp-auth.login.show')
        );
    }
}

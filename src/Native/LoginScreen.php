<?php

namespace Laratribe\NativephpAuth\Native;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Native\Mobile\Edge\NativeComponent;

class LoginScreen extends NativeComponent
{
    public string $email = '';

    public string $password = '';

    public bool $loading = false;

    public ?string $error = null;

    public function login(): void
    {
        $this->error = null;

        $validator = Validator::make(
            ['email' => $this->email, 'password' => $this->password],
            ['email' => ['required', 'email'], 'password' => ['required']],
        );

        if ($validator->fails()) {
            $this->error = $validator->errors()->first();

            return;
        }

        $this->loading = true;

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->loading = false;
            $this->error = 'These credentials do not match our records.';

            return;
        }

        $user = Auth::user();

        // Checked directly against email_verified_at, same reasoning as the
        // web-view LoginController: this doesn't require the app's User
        // model to implement MustVerifyEmail.
        if (config('nativephp-auth.require_verified_email', true) && is_null($user->email_verified_at)) {
            Auth::logout();
            $this->loading = false;

            $this->navigate($this->route('nativephp-auth.native.otp'), [
                'email' => $this->email,
                'type' => 'register',
            ]);

            return;
        }

        $this->loading = false;

        $this->replace(config('nativephp-auth.native.redirects.after_login', '/home'));
    }

    // Explicit render(), rather than relying on Livewire-style path
    // auto-discovery, since this class lives in a package namespace
    // (Laratribe\NativephpAuth\Native) rather than the host app's
    // conventional App\NativeComponents namespace.
    public function render(): View
    {
        return view('nativephp-auth::native.login');
    }
}

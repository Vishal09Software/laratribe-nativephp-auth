<?php

namespace Laratribe\NativephpAuth\Native;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Native\Mobile\Edge\NativeComponent;
use Laratribe\NativephpAuth\Services\OtpService;

class OtpScreen extends NativeComponent
{
    public string $email = '';

    public string $type = 'register';

    public string $code = '';

    public bool $loading = false;

    public ?string $error = null;

    public ?string $status = null;

    /**
     * Reads the data passed in from LoginScreen/RegisterScreen/
     * ForgotPasswordScreen's $this->navigate(route, ['email' => ..., 'type' => ...]).
     */
    public function mount(): void
    {
        $this->email = $this->data('email', '');
        $this->type = $this->data('type', 'register');
    }

    public function verify(): void
    {
        $this->error = null;

        if (trim($this->code) === '') {
            $this->error = 'Enter the code we sent you.';

            return;
        }

        $this->loading = true;

        $ok = app(OtpService::class)->verify($this->email, $this->code, $this->type);

        if (! $ok) {
            $this->loading = false;
            $this->error = 'That code is invalid or has expired.';

            return;
        }

        if ($this->type === 'register') {
            $userModel = config('auth.providers.users.model', \App\Models\User::class);
            $user = $userModel::where('email', $this->email)->first();

            if ($user && is_null($user->email_verified_at)) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            if ($user) {
                Auth::login($user);
            }

            $this->loading = false;

            // replace(), not navigate(): pops the whole login/register/otp
            // stack so the device back button doesn't return to the auth
            // flow once the user is signed in.
            $this->replace(config('nativephp-auth.native.redirects.after_register_verified', '/home'));

            return;
        }

        $this->loading = false;

        $this->navigate($this->route('nativephp-auth.native.reset-password'), [
            'email' => $this->email,
        ]);
    }

    public function resend(): void
    {
        $this->status = null;
        $this->error = null;

        try {
            app(OtpService::class)->generateAndSend($this->email, $this->type);
            $this->status = config('nativephp-auth.text.otp.resent_message', 'A new code has been sent.');
        } catch (\RuntimeException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render(): View
    {
        return view('nativephp-auth::native.otp');
    }
}

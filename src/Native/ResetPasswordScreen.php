<?php

namespace Laratribe\NativephpAuth\Native;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Native\Mobile\Edge\NativeComponent;
use Laratribe\NativephpAuth\Services\OtpService;

class ResetPasswordScreen extends NativeComponent
{
    public string $email = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public bool $loading = false;

    public ?string $error = null;

    public function mount(): void
    {
        $this->email = $this->data('email', '');

        // Gate this screen the same way the web-view controller does: you
        // can only land here after a verified OTP for this email in the
        // last 15 minutes. A deep link straight to this route bounces you
        // back to the forgot-password screen instead of showing a form
        // with nothing behind it.
        if (trim($this->email) === '' || ! app(OtpService::class)->hasRecentlyVerified($this->email, 'reset-password')) {
            $this->replace($this->route('nativephp-auth.native.forgot-password'));
        }
    }

    public function reset(): void
    {
        $this->error = null;

        $validator = Validator::make(
            [
                'password' => $this->password,
                'password_confirmation' => $this->passwordConfirmation,
            ],
            ['password' => ['required', 'string', 'min:8', 'confirmed']],
        );

        if ($validator->fails()) {
            $this->error = $validator->errors()->first();

            return;
        }

        if (! app(OtpService::class)->hasRecentlyVerified($this->email, 'reset-password')) {
            $this->error = 'Your reset session expired. Please request a new code.';
            $this->replace($this->route('nativephp-auth.native.forgot-password'));

            return;
        }

        $this->loading = true;

        $userModel = config('auth.providers.users.model', \App\Models\User::class);
        $user = $userModel::where('email', $this->email)->first();

        if (! $user) {
            $this->loading = false;
            $this->error = 'We could not find an account with that email.';

            return;
        }

        $user->forceFill(['password' => Hash::make($this->password)])->save();

        $this->loading = false;

        $this->replace($this->route('nativephp-auth.native.login'));
    }

    public function render(): View
    {
        return view('nativephp-auth::native.reset-password');
    }
}

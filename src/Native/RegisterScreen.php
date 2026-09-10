<?php

namespace Laratribe\NativephpAuth\Native;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Native\Mobile\Edge\NativeComponent;
use Laratribe\NativephpAuth\Services\OtpService;

class RegisterScreen extends NativeComponent
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public bool $loading = false;

    public ?string $error = null;

    public function register(): void
    {
        $this->error = null;

        $validator = Validator::make(
            [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->passwordConfirmation,
            ],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],
        );

        if ($validator->fails()) {
            $this->error = $validator->errors()->first();

            return;
        }

        $this->loading = true;

        $userModel = config('auth.providers.users.model', \App\Models\User::class);

        $user = $userModel::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        try {
            app(OtpService::class)->generateAndSend($user->email, 'register');
        } catch (\RuntimeException $e) {
            // Non-fatal — the OTP screen has its own resend action.
        }

        $this->loading = false;

        $this->navigate($this->route('nativephp-auth.native.otp'), [
            'email' => $user->email,
            'type' => 'register',
        ]);
    }

    public function goBack(): void
    {
        $this->back();
    }

    public function render(): View
    {
        return view('nativephp-auth::native.register');
    }
}

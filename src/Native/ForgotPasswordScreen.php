<?php

namespace Laratribe\NativephpAuth\Native;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Native\Mobile\Edge\NativeComponent;
use Laratribe\NativephpAuth\Services\OtpService;

class ForgotPasswordScreen extends NativeComponent
{
    public string $email = '';

    public bool $loading = false;

    public ?string $error = null;

    public function send(): void
    {
        $this->error = null;

        $validator = Validator::make(
            ['email' => $this->email],
            ['email' => ['required', 'string', 'email', 'exists:users,email']],
        );

        if ($validator->fails()) {
            $this->error = $validator->errors()->first();

            return;
        }

        $this->loading = true;

        try {
            app(OtpService::class)->generateAndSend($this->email, 'reset-password');
        } catch (\RuntimeException $e) {
            $this->loading = false;
            $this->error = $e->getMessage();

            return;
        }

        $this->loading = false;

        $this->navigate($this->route('nativephp-auth.native.otp'), [
            'email' => $this->email,
            'type' => 'reset-password',
        ]);
    }

    public function goBack(): void
    {
        $this->back();
    }

    public function render(): View
    {
        return view('nativephp-auth::native.forgot-password');
    }
}

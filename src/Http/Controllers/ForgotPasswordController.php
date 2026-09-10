<?php

namespace Laratribe\NativephpAuth\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Laratribe\NativephpAuth\Http\Requests\ForgotPasswordRequest;
use Laratribe\NativephpAuth\Services\OtpService;

class ForgotPasswordController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    public function show(): View
    {
        return view('nativephp-auth::auth.forgot-password');
    }

    public function send(ForgotPasswordRequest $request): RedirectResponse
    {
        $email = $request->input('email');

        try {
            $this->otpService->generateAndSend($email, 'reset-password');
        } catch (\RuntimeException $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }

        session(['nativephp_auth.pending_identifier' => $email]);

        return redirect()
            ->route('nativephp-auth.otp.show', ['type' => 'reset-password'])
            ->with('status', 'We sent a password reset code to your email.');
    }
}

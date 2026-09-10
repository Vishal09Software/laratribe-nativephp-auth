<?php

namespace Laratribe\NativephpAuth\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Laratribe\NativephpAuth\Http\Requests\RegisterRequest;
use Laratribe\NativephpAuth\Services\OtpService;

class RegisterController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    public function show(): View
    {
        return view('nativephp-auth::auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $userModel = config('auth.providers.users.model', \App\Models\User::class);

        $user = $userModel::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        try {
            $this->otpService->generateAndSend($user->email, 'register');
        } catch (\RuntimeException $e) {
            // Non-fatal — user can request a resend from the OTP screen.
        }

        session(['nativephp_auth.pending_identifier' => $user->email]);

        return redirect()
            ->route('nativephp-auth.otp.show', ['type' => 'register'])
            ->with('status', 'We sent a verification code to your email.');
    }
}

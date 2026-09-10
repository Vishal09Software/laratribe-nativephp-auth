<?php

namespace Laratribe\NativephpAuth\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Laratribe\NativephpAuth\Http\Requests\ResetPasswordRequest;
use Laratribe\NativephpAuth\Services\OtpService;

class ResetPasswordController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    public function show(): View|RedirectResponse
    {
        $identifier = session('nativephp_auth.pending_identifier');

        if (! $identifier || ! $this->otpService->hasRecentlyVerified($identifier, 'reset-password')) {
            return redirect()->route('nativephp-auth.password.forgot');
        }

        return view('nativephp-auth::auth.reset-password', ['identifier' => $identifier]);
    }

    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $identifier = $request->input('email');

        // Two checks, not one: hasRecentlyVerified() alone only proves that
        // *some* OTP for this email was verified in the last 15 minutes —
        // it does not prove that whoever is submitting this form is the
        // same person who verified it. Anyone who knew the victim's email
        // could POST straight to this route within that window and reset
        // the password from a completely different session/device. Tying
        // it to session('nativephp_auth.pending_identifier') closes that
        // gap: only the browser session that completed the OTP step for
        // this exact identifier can complete the reset.
        $sessionIdentifier = session('nativephp_auth.pending_identifier');

        if ($sessionIdentifier !== $identifier
            || ! $this->otpService->hasRecentlyVerified($identifier, 'reset-password')
        ) {
            return redirect()
                ->route('nativephp-auth.password.forgot')
                ->withErrors(['email' => 'Your reset session expired. Please request a new code.']);
        }

        $userModel = config('auth.providers.users.model', \App\Models\User::class);
        $user = $userModel::where('email', $identifier)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'We could not find an account with that email.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->input('password')),
        ])->save();

        session()->forget('nativephp_auth.pending_identifier');

        return redirect(
            config('nativephp-auth.redirects.after_password_reset') ?? route('nativephp-auth.login.show')
        )->with('status', 'Your password has been updated. You can now log in.');
    }
}

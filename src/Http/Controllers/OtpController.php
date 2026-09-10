<?php

namespace Laratribe\NativephpAuth\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laratribe\NativephpAuth\Http\Requests\VerifyOtpRequest;
use Laratribe\NativephpAuth\Services\OtpService;

class OtpController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    public function show(Request $request): View|RedirectResponse
    {
        $type = $request->query('type', 'register') === 'reset-password'
            ? 'reset-password'
            : 'register';

        $identifier = session('nativephp_auth.pending_identifier');

        if (! $identifier) {
            return redirect()->route(
                $type === 'reset-password'
                    ? 'nativephp-auth.password.forgot'
                    : 'nativephp-auth.register.show'
            );
        }

        return view('nativephp-auth::auth.verify-otp', [
            'identifier' => $identifier,
            'type' => $type,
        ]);
    }

    public function verify(VerifyOtpRequest $request): RedirectResponse
    {
        // Plain strings via ->input(), not ->string() (which returns a
        // Stringable). Stringable objects passed into an array that later
        // gets bound to a PDO statement (e.g. a where() clause built from
        // it) can throw "could not be converted to string" on some drivers.
        $identifier = $request->input('email');
        $type = $request->input('type');
        $code = $request->input('code');

        $ok = $this->otpService->verify($identifier, $code, $type);

        if (! $ok) {
            return back()
                ->withErrors(['code' => 'That code is invalid or has expired.'])
                ->withInput();
        }

        if ($type === 'register') {
            $userModel = config('auth.providers.users.model', \App\Models\User::class);
            $user = $userModel::where('email', $identifier)->first();

            if ($user && is_null($user->email_verified_at)) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            if ($user) {
                Auth::login($user);
            }

            session()->forget('nativephp_auth.pending_identifier');

            return redirect(config('nativephp-auth.redirects.after_register_verified', '/home'))
                ->with('status', 'Your account has been verified.');
        }

        // reset-password flow: mark verified, keep identifier, move to reset form
        session(['nativephp_auth.pending_identifier' => $identifier]);

        return redirect()
            ->route('nativephp-auth.password.reset.show')
            ->with('status', 'Code verified. You can now set a new password.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $identifier = session('nativephp_auth.pending_identifier');
        $type = $request->input('type', 'register') === 'reset-password'
            ? 'reset-password'
            : 'register';

        if (! $identifier) {
            return redirect()->route('nativephp-auth.login.show');
        }

        try {
            $this->otpService->generateAndSend($identifier, $type);
            $message = config('nativephp-auth.text.otp.resent_message', 'A new code has been sent.');
        } catch (\RuntimeException $e) {
            $message = $e->getMessage();
        }

        return back()->with('status', $message);
    }
}

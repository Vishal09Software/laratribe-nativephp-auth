<?php

namespace Laratribe\NativephpAuth\Services;

use Illuminate\Support\Facades\Notification;
use Laratribe\NativephpAuth\Models\Otp;
use Laratribe\NativephpAuth\Notifications\OtpNotification;

class OtpService
{
    /**
     * Generate a new OTP, invalidating any previous unverified OTP
     * of the same type for the same identifier, then send it.
     */
    public function generateAndSend(string $identifier, string $type = 'register'): Otp
    {
        $recent = Otp::where('identifier', $identifier)
            ->where('type', $type)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        $throttle = (int) config('nativephp-auth.otp.throttle_seconds', 60);

        if ($recent && $recent->created_at->diffInSeconds(now()) < $throttle) {
            throw new \RuntimeException(
                'Please wait a moment before requesting another code.'
            );
        }

        $length = (int) config('nativephp-auth.otp.length', 6);
        $code = (string) random_int(
            (int) str_pad('1', $length, '0'),
            (int) str_pad('9', $length, '9')
        );

        $otp = Otp::create([
            'identifier' => $identifier,
            'code' => $code,
            'type' => $type,
            'expires_at' => now()->addMinutes(
                (int) config('nativephp-auth.otp.expires_in_minutes', 10)
            ),
        ]);

        Notification::route('mail', $identifier)
            ->notify(new OtpNotification($code, $type));

        return $otp;
    }

    /**
     * Verify a submitted code for a given identifier/type.
     */
    public function verify(string $identifier, string $code, string $type = 'register'): bool
    {
        $otp = Otp::where('identifier', $identifier)
            ->where('type', $type)
            ->where('code', $code)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (! $otp || $otp->isExpired()) {
            return false;
        }

        $otp->update(['verified_at' => now()]);

        return true;
    }

    /**
     * Whether this identifier has a verified OTP of the given type
     * within the last N minutes — used to gate the reset-password step.
     */
    public function hasRecentlyVerified(string $identifier, string $type, int $withinMinutes = 15): bool
    {
        return Otp::where('identifier', $identifier)
            ->where('type', $type)
            ->whereNotNull('verified_at')
            ->where('verified_at', '>=', now()->subMinutes($withinMinutes))
            ->exists();
    }
}

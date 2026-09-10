<?php

namespace Laratribe\NativephpAuth\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    public function __construct(
        protected string $code,
        protected string $type = 'register',
    ) {}

    public function via(object $notifiable): array
    {
        $channel = config('nativephp-auth.otp.channel', 'mail');

        return [$channel === 'log' ? 'log' : 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('nativephp-auth.branding.app_name', config('app.name'));
        $minutes = config('nativephp-auth.otp.expires_in_minutes', 10);

        $subject = $this->type === 'reset-password'
            ? "Your {$appName} password reset code"
            : "Your {$appName} verification code";

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello!')
            ->line("Your verification code is:")
            ->line("**{$this->code}**")
            ->line("This code expires in {$minutes} minutes.")
            ->line('If you did not request this, you can safely ignore this email.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'code' => $this->code,
            'type' => $this->type,
        ];
    }
}

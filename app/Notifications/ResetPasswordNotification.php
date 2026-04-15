<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Redefinição de senha - Academia Top Fitness')
            ->greeting('Olá!')
            ->line('Recebemos uma solicitação para redefinir a senha da sua conta.')
            ->action('Redefinir senha', $resetUrl)
            ->line('Este link expira em ' . config('auth.passwords.'.config('auth.defaults.passwords').'.expire') . ' minutos.')
            ->line('Se você não solicitou a redefinição, nenhuma ação é necessária.')
            ->salutation(Lang::get('Academia Top Fitness'));
    }
}

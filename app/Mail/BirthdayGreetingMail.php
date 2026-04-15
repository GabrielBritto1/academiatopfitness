<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BirthdayGreetingMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $studentName,
        public string $messageBody,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Feliz Aniversário - Academia Top Fitness',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.birthday-greeting',
        );
    }
}

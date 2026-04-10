<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $studentName,
        public string $dueDate,
        public ?string $unitName = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lembrete de vencimento da mensalidade - Academia Top Fitness',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-reminder',
            with: [
                'studentName' => $this->studentName,
                'dueDate' => $this->dueDate,
                'unitName' => $this->unitName,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

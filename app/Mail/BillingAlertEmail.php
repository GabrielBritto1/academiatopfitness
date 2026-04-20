<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BillingAlertEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $studentName,
        public string $subjectLine,
        public string $messageBody,
        public string $dueDate,
        public string $amount,
        public string $transactionDescription,
        public ?string $unitName = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.billing-alert',
            with: [
                'studentName' => $this->studentName,
                'messageBody' => $this->messageBody,
                'dueDate' => $this->dueDate,
                'amount' => $this->amount,
                'transactionDescription' => $this->transactionDescription,
                'unitName' => $this->unitName,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

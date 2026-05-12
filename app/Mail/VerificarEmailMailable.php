<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificarEmailMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $email,
        public readonly string $nome,
        public readonly string $codigo,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirme seu e-mail no Workinhoo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verificar-email',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

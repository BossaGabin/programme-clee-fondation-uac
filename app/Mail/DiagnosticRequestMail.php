<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class DiagnosticRequestMail extends Mailable
{
    public function __construct(public string $name) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Demande de diagnostic reçue - Programme CLEE'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.diagnostic-request',
            with: ['name' => $this->name]
        );
    }
}
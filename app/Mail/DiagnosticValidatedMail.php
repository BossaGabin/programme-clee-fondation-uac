<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class DiagnosticValidatedMail extends Mailable
{
    public function __construct(
        public string $candidatName,
        public string $coachName
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre demande de diagnostic a été validée - Programme CLEE'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.diagnostic-validated',
            with: [
                'candidatName' => $this->candidatName,
                'coachName'    => $this->coachName,
            ]
        );
    }
}
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoachRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $coachName,
        public string $candidatName,
        public string $reason
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Affectation rejetée — Action requise',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.coach-rejected',
        );
    }
}
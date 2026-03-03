<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class EntretienProgrammeMail extends Mailable
{
    public function __construct(
        public string  $candidatName,
        public string  $coachName,
        public string  $date,
        public string  $heure,
        public string  $mode,
        public ?string $location,
        public ?string $meetingLink
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre entretien a été programmé - Programme CLEE'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.entretien-programme',
        );
    }
}
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EntretienConfirmeCoachMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string  $coachName,
        public string  $candidatName,
        public string  $date,
        public string  $heure,
        public string  $mode,
        public ?string $location    = null,
        public ?string $meetingLink = null,
        public ?string $plateforme  = null,
        public ?string $numeroWhatsapp = null,
        public ?string $numeroAppel    = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Entretien confirmé par le candidat',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.entretien-confirme-coach',
        );
    }
}
<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\DiagnosticRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoachAffectationPendingMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string             $coachName,
        public string             $candidatName,
        public Carbon             $expiresAt,
        public int                $assignmentId,
        public DiagnosticRequest  $demande        // ← ajouter
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle affectation — Action requise sous 8h',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.coach-affectation-pending',
        );
    }
}
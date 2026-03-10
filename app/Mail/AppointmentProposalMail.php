<?php

namespace App\Mail;

use App\Models\AppointmentProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentProposalMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string              $candidatName,
        public string              $coachName,
        public AppointmentProposal $proposal
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre coach vous propose 3 horaires d\'entretien',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-proposal',
        );
    }
}
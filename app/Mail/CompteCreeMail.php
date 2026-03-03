<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class CompteCreeMail extends Mailable
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $role,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre compte Programme CLEE a été créé'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.compte-cree',
            with: [
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => $this->password,
                'role'     => $this->role,
            ]
        );
    }
}
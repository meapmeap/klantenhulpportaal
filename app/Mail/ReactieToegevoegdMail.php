<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\Reactie;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReactieToegevoegdMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public Reactie $reactie
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nieuwe reactie op je ticket'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reactie-toegevoegd'
        );
    }
}
<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Contact $contact)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouveau message Zérolib : ' . $this->contact->sujet,
            replyTo: [$this->contact->email], // Permet de faire "Répondre" directement à l'utilisateur
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact',
        );
    }
}

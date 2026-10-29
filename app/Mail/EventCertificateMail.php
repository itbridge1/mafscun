<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventCertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $event;
    public $filePath;

    public function __construct($participant, $event, $filePath)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->filePath = $filePath;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Certificate of Participation: ' . $this->event->title,
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.event_certificate',
        );
    }

    public function attachments()
    {
        return [
            Attachment::fromPath($this->filePath)->as('Certificate.jpg')->withMime('image/jpeg'),
        ];
    }
}

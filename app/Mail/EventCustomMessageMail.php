<?php

namespace App\Mail;

use App\Models\EventBooking;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventCustomMessageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public EventBooking $booking;
    public string $subjectText;
    public string $messageText;

    public function __construct(EventBooking $booking, string $subjectText, string $messageText)
    {
        $this->booking = $booking;
        $this->subjectText = $subjectText;
        $this->messageText = $messageText;
    }

    public function build(): static
    {
        $siteSetting = SiteSetting::first();

        return $this->view('emails.customMessage')
            ->with([
                'booking'     => $this->booking,
                'siteSetting' => $siteSetting,
                'messageText' => $this->messageText,
            ])
            ->subject($this->subjectText);
    }
}

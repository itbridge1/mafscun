<?php

namespace App\Mail;

use App\Models\EventBooking;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class EventBookingMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public EventBooking $booking;

    public function __construct(EventBooking $booking)
    {
        $this->booking = $booking;
    }

    public function build(): static
    {
        $siteSetting = SiteSetting::first();

        return $this->view('emails.eventBooking')
            ->with([
                'booking'     => $this->booking,
                'siteSetting' => $siteSetting,
            ])
            ->subject('Event Entry Ticket Cards – ' . $this->booking->event->title);
    }
}

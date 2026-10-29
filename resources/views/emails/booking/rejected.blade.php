<x-mail::message>
# Booking Rejected

Dear User,

We regret to inform you that your booking for the event **{{ $booking->event->title }}** has been rejected.

@if($booking->rejection_note)
**Reason for rejection:**
> {{ $booking->rejection_note }}
@endif

If you have any questions, please contact our support team.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

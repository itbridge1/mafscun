@component('mail::message')
# Booking Rejected

Dear {{ $booking->user ? $booking->user->name : 'Participant' }},

We regret to inform you that your booking for the event **{{ $booking->event->title ?? 'Bagmati Proscun Event' }}** has been rejected.

@if($booking->rejection_note)
**Reason for Rejection:**
{{ $booking->rejection_note }}
@endif

If you have any questions, please contact our support team.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

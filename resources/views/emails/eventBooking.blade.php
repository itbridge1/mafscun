<!DOCTYPE html>
<html>
<head>
    <title>Event Entry Ticket Cards</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f0f0f0;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.85em;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Event Entry Cards</h2>
        <p>Dear Participant(s),</p>
        <p>Your Event entry cards for <strong>{{ $booking->event->title }}</strong> have been issued.</p>
        <p>Please click the secure link below to view and download your ID Cards in PDF format or print them directly.</p>
        <br>
        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ route('event.booking.ticket', $booking->booking_token) }}" style="background-color: #007bff; color: #fff; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                View & Download ID Cards
            </a>
        </div>
        <br>
        <p>Or copy and paste this URL into your browser:</p>
        <p><a href="{{ route('event.booking.ticket', $booking->booking_token) }}">{{ route('event.booking.ticket', $booking->booking_token) }}</a></p>
        <p>Thank you,</p>
        <p>{{ $siteSetting->title ?? 'Bagmati Proscun' }}</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $siteSetting->title ?? 'Bagmati Proscun' }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

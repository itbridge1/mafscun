<!DOCTYPE html>
<html>
<head>
    <title>{{ $subjectText ?? 'Update regarding Event' }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f9f9f9; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-top: 5px solid #28a745; }
        .header { text-align: center; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .header img { max-width: 200px; }
        .content h2 { color: #28a745; margin-top: 0; }
        .message-box { background: #f8f9fa; border-left: 4px solid #28a745; padding: 15px; border-radius: 4px; margin: 20px 0; white-space: pre-line; }
        .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 0.9em; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(isset($siteSetting) && $siteSetting->site_logo)
                <img src="{{ url(\App\Facades\ViewHelper::getImagePath('siteSetting', $siteSetting->site_logo)) }}" alt="{{ $siteSetting->title ?? 'Logo' }}">
            @else
                <h2>{{ $siteSetting->title ?? 'Bagmati Proscun' }}</h2>
            @endif
        </div>
        
        <div class="content">
            <h2>Important Update for: {{ $booking->event->title }}</h2>
            <p>Dear {{ $booking->participants->first()->name ?? 'Participant' }},</p>
            
            <p>We are writing to send you an important update regarding the event. Please see the message details below:</p>
            
            <div class="message-box">
                {!! nl2br(e($messageText)) !!}
            </div>

            <div style="text-align: center; margin: 20px 0;">
                @php
                    $qrData = json_encode([
                        'booking_id' => $booking->id,
                        'token' => $booking->booking_token,
                        'event' => $booking->event->title
                    ]);
                @endphp
                <img src="https://quickchart.io/qr?text={{ urlencode($qrData) }}&size=150&margin=1" alt="QR Code" style="display: block; margin: 0 auto; width: 150px; height: 150px;" />
                <p style="margin-top: 10px; font-weight: bold; color: #28a745; font-size: 0.95em;">Please present this QR for entry to the event.</p>
            </div>

            <p><strong>Booking Reference:</strong> #BKM-{{ sprintf('%05d', $booking->id) }}</p>
            <p><strong>Booking Token:</strong> {{ $booking->booking_token }}</p>
        </div>

        <div class="footer">
            <p>This is an automated notification. Please do not reply directly to this email.</p>
            <p>&copy; {{ date('Y') }} {{ $siteSetting->title ?? 'Bagmati Proscun' }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

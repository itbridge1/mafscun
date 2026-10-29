<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking ID Cards</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
        }
        .page {
            width: 100%;
            padding: 10mm;
            box-sizing: border-box;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5mm; /* Adds space between cards */
        }
        td.card-cell {
            width: 50%;
            height: 128.5mm; /* Adjusted height to account for spacing */
            vertical-align: top;
            padding: 0;
        }
        .id-card {
            width: 100%;
            height: 100%;
            border: 1px solid #e0dacb;
            border-radius: 12px;
            padding: 15px;
            box-sizing: border-box;
            background-color: #fcf9f2;
        }
        .card-header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 1px dashed #e0dacb;
            padding-bottom: 8px;
        }
        .card-header img {
            max-height: 40px;
            margin-bottom: 3px;
        }
        .card-header h3 {
            margin: 0;
            font-size: 13px;
            color: #6e6552;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .event-title {
            font-size: 15px;
            font-weight: bold;
            color: #2c2518;
            text-align: center;
            margin-bottom: 10px;
            line-height: 1.3;
        }
        .info-section {
            margin-bottom: 10px;
            padding-left: 10px;
        }
        .info-row {
            margin-bottom: 6px;
            font-size: 11px;
            color: #555;
            display: table;
            width: 100%;
        }
        .info-row .icon {
            display: table-cell;
            width: 20px;
            font-size: 12px;
            text-align: center;
            vertical-align: top;
        }
        .info-row .val {
            display: table-cell;
            vertical-align: top;
            line-height: 1.2;
        }
        .participant-section {
            text-align: center;
            margin: 8px 0;
        }
        .participant-name {
            font-size: 18px;
            font-weight: bold;
            color: #1e1a12;
            margin: 0;
        }
        .participant-designation {
            font-size: 12px;
            color: #6e6552;
            margin-top: 2px;
            font-weight: normal;
        }
        .qr-section {
            text-align: center;
            margin-top: 15px;
        }
        .qr-section img {
            width: 110px;
            height: 110px;
            display: block;
            margin: 0 auto;
            border: 1px solid #eee;
            padding: 4px;
            background: #fff;
        }
        .qr-title {
            font-size: 11px;
            font-weight: bold;
            color: #333;
            margin-top: 5px;
            margin-bottom: 2px;
        }
        .qr-instruction {
            font-size: 9px;
            color: #777;
            text-align: center;
            line-height: 1.2;
            max-width: 90%;
            margin: 0 auto;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="page">
        <table>
            <tr>
            @foreach($booking->participants as $index => $participant)
                <td class="card-cell">
                    <div class="id-card">
                        <div class="card-header">
                            @if(isset($siteSetting) && $siteSetting->site_logo && file_exists(public_path('images/siteSetting/' . $siteSetting->site_logo)))
                                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/siteSetting/' . $siteSetting->site_logo))) }}" alt="Logo">
                            @endif
                            <h3>{{ $siteSetting->title ?? 'Bagmati Proscun' }}</h3>
                        </div>
                        
                        <div>
                            <div class="event-title">
                                {{ $booking->event->title }}
                            </div>
                            
                            <div class="info-section">
                                <div class="info-row">
                                    <div class="icon">📅</div>
                                    <div class="val">
                                        {{ $booking->event->start_date_bs }} - {{ $booking->event->end_date_bs }}<br>
                                        ({{ \Carbon\Carbon::parse($booking->event->start_date)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($booking->event->end_date)->format('d M, Y') }})
                                    </div>
                                </div>
                                
                                <div class="info-row">
                                    <div class="icon">📞</div>
                                    <div class="val">{{ $siteSetting->phone }}</div>
                                </div>
                                
                                <div class="info-row">
                                    <div class="icon">📍</div>
                                    <div class="val">{{ $booking->event->getLocationName() ?: $siteSetting->location }}</div>
                                </div>
                            </div>

                            <div class="participant-section">
                                <h4 class="participant-name">{{ $participant->name }}</h4>
                                @if($participant->designation)
                                    <div class="participant-designation">{{ $participant->designation }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="qr-section">
                            @if(isset($participantQrs[$participant->id]))
                                <img src="data:image/png;base64,{{ $participantQrs[$participant->id] }}" alt="QR Code">
                            @else
                                <p><em>QR Code not available</em></p>
                            @endif
                            <div class="qr-title">Course QR Code</div>
                            <div class="qr-instruction">Scan the QR code upon course entry to secure your attendance for the enrolled class.</div>
                        </div>
                    </div>
                </td>
                
                @if (($index + 1) % 2 == 0)
                    </tr>
                    @if (($index + 1) % 4 != 0 && !$loop->last)
                        <tr>
                    @endif
                @endif
                
                @if (($index + 1) % 4 == 0 && !$loop->last)
                    </table>
                    </div>
                    <div class="page-break"></div>
                    <div class="page">
                    <table>
                    <tr>
                @endif
            @endforeach
            
            @if (count($booking->participants) % 2 != 0)
                <td class="card-cell"></td></tr>
            @endif
        </table>
    </div>
</body>
</html>

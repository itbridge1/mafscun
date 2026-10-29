<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ $participant->name }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .a4-page {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 0 auto;
            padding: 10mm;
            box-sizing: border-box;
            display: flex;
            flex-wrap: wrap;
            align-content: flex-start;
        }
        .card-container {
            width: 50%;
            height: 138.5mm; /* 1/4 of A4 approximately */
            padding: 5mm;
            box-sizing: border-box;
        }
        .id-card {
            width: 100%;
            height: 100%;
            border: 1px solid #e0dacb;
            border-radius: 12px;
            padding: 15px;
            box-sizing: border-box;
            background-color: #fcf9f2;
            position: relative;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
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
            object-fit: contain;
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
            display: flex;
            align-items: flex-start;
            margin-bottom: 6px;
            font-size: 11px;
            color: #555;
        }
        .info-row .icon {
            width: 20px;
            font-size: 12px;
            margin-right: 5px;
            text-align: center;
        }
        .info-row .val {
            flex: 1;
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
        
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .a4-page {
                margin: 0;
                box-shadow: none;
                padding: 10mm;
            }
            .id-card {
                box-shadow: none;
                border: 1px solid #ccc;
            }
        }
    </style>
</head>
<body>
    <div class="a4-page">
        <div class="card-container">
            <div class="id-card">
                <div class="card-header">
                    @if($siteSetting->site_logo)
                        <img src="{{ \App\Facades\ViewHelper::getImagePath('siteSetting', $siteSetting->site_logo) }}" alt="Logo">
                    @endif
                    <h3>{{ $siteSetting->title }}</h3>
                </div>
                
                <div>
                    <div class="event-title">
                        {{ $participant->booking->event->title ?? 'Event' }}
                    </div>
                    
                    <div class="info-section">
                        <div class="info-row">
                            <span class="icon">📅</span>
                            <span class="val">
                                {{ $participant->booking->event->start_date_bs ?? '' }} - {{ $participant->booking->event->end_date_bs ?? '' }}
                                @if(isset($participant->booking->event->start_date))
                                    <br>
                                    ({{ \Carbon\Carbon::parse($participant->booking->event->start_date)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($participant->booking->event->end_date)->format('d M, Y') }})
                                @endif
                            </span>
                        </div>
                        
                        <div class="info-row">
                            <span class="icon">📞</span>
                            <span class="val">{{ $siteSetting->phone }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="icon">📍</span>
                            <span class="val">{{ $participant->booking->event->getLocationName() ?: $siteSetting->location }}</span>
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
                    <img src="data:image/png;base64,{{ $participant->qr_code }}" alt="QR Code">
                    <div class="qr-title">Course QR Code</div>
                    <div class="qr-instruction">Scan the QR code upon course entry to secure your attendance for the enrolled class.</div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>

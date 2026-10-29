<!DOCTYPE html>
<html>
<head>
    <title>Login Verification Code</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f9f9f9; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-top: 5px solid #28a745; }
        .header { text-align: center; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .header img { max-width: 200px; }
        .content h2 { color: #28a745; text-align: center; }
        .otp-code { font-size: 2.2rem; font-weight: bold; letter-spacing: 5px; color: #28a745; text-align: center; background: #f8f9fa; padding: 15px; border-radius: 6px; border: 1px dashed #28a745; margin: 25px 0; }
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
            <h2>Your Login Verification Code</h2>
            <p>Hello,</p>
            <p>Please use the following 6-digit verification code to complete your login / registration process. This code is valid for 10 minutes.</p>
            
            <div class="otp-code">
                {{ $otpCode }}
            </div>

            <p>If you did not request this code, please ignore this email.</p>
        </div>

        <div class="footer">
            <p>This is an automated notification. Please do not reply directly to this email.</p>
            <p>&copy; {{ date('Y') }} {{ $siteSetting->title ?? 'Bagmati Proscun' }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

<?php

namespace App\Mail;

use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otpCode;

    public function __construct(string $otpCode)
    {
        $this->otpCode = $otpCode;
    }

    public function build(): static
    {
        $siteSetting = SiteSetting::first();

        return $this->view('emails.userOtp')
            ->with([
                'otpCode'     => $this->otpCode,
                'siteSetting' => $siteSetting,
            ])
            ->subject('Your Login Verification Code – ' . ($siteSetting->title ?? 'Bagmati Proscun'));
    }
}

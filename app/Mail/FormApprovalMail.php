<?php

namespace App\Mail;

use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $formDetails;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($formDetails)
    {
        $this->formDetails = $formDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.formApproval')
                    ->with([
                        'formDetails' => $this->formDetails,
                        'phone'=>SiteSetting::first()->phone??null,
                        'email'=>SiteSetting::first()->email??null,
                    ])
                    ->subject(subject: 'AGM  Detail');
    }
}
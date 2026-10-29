<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Admin\EventController;
use Illuminate\Support\Facades\Log;

class SendCertificateEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;
    protected $participants;
    protected $bgSource;
    protected $withDesignationSettings;
    protected $withoutDesignationSettings;
    protected $allSettings;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event, $participants, $bgSource, $withDesignationSettings, $withoutDesignationSettings, $allSettings)
    {
        $this->event = $event;
        $this->participants = $participants;
        $this->bgSource = $bgSource;
        $this->withDesignationSettings = $withDesignationSettings;
        $this->withoutDesignationSettings = $withoutDesignationSettings;
        $this->allSettings = $allSettings;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->participants as $p) {
            try {
                $savePath = EventController::generateCertificateImage(
                    $this->event, 
                    $p, 
                    $this->bgSource, 
                    $this->withDesignationSettings, 
                    $this->withoutDesignationSettings, 
                    $this->allSettings
                );
                
                $email = $p->email ?: ($p->booking->contact_email ?? null);
                if ($email) {
                    Mail::to($email)->send(new \App\Mail\EventCertificateMail($p, $this->event, $savePath));
                    // Update sent time
                    $p->update(['certificate_sent_at' => now()]);
                }
            } catch (\Exception $e) {
                Log::error('Certificate email failed for participant ' . $p->id . ': ' . $e->getMessage());
            }
        }
    }
}

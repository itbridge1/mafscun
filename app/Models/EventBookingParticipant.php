<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventBookingParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_booking_id',
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'dob_bs',
        'phone',
        'email',
        'gender',
        'designation',
        'token',
        'qr_code',
        'is_present',
        'attendance_dates',
        'certificate_sent_at',
        'custom_answers',
    ];

    protected $casts = [
        'attendance_dates' => 'array',
        'certificate_sent_at' => 'datetime',
        'custom_answers' => 'array',
    ];

    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventParticipantAnswer::class);
    }

    public function getAnswerForQuestion($questionId)
    {
        if ($this->relationLoaded('answers')) {
            $ans = $this->answers->firstWhere('event_question_id', $questionId);
            return $ans ? $ans->formatted_answer : null;
        }

        if (!empty($this->custom_answers) && isset($this->custom_answers[$questionId])) {
            $val = $this->custom_answers[$questionId];
            return is_array($val) ? implode(', ', $val) : (string) $val;
        }

        $ans = $this->answers()->where('event_question_id', $questionId)->first();
        return $ans ? $ans->formatted_answer : null;
    }

    public function getNameAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }
        
        $parts = array_filter([$this->first_name, $this->middle_name, $this->last_name]);
        return implode(' ', $parts);
    }

    protected static function booted()
    {
        static::creating(function ($participant) {
            if (empty($participant->token)) {
                $participant->token = strtoupper(\Illuminate\Support\Str::random(12));
            }
        });
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(EventBooking::class, 'event_booking_id');
    }

    public function getQrPayloadArray(): array
    {
        $eventTitle = '';
        if ($this->relationLoaded('booking') && $this->booking) {
            $eventTitle = $this->booking->event ? $this->booking->event->title : '';
        } else {
            $booking = EventBooking::with('event')->find($this->event_booking_id);
            $eventTitle = $booking && $booking->event ? $booking->event->title : '';
        }

        return [
            'booking_id'     => (int) $this->event_booking_id,
            'token'          => (string) $this->token,
            'event'          => (string) $eventTitle,
            'participant_id' => (int) $this->id,
        ];
    }

    public function generateQrCodeBase64(): string
    {
        $options = new \chillerlan\QRCode\QROptions([
            'outputInterface' => \chillerlan\QRCode\Output\QRGdImagePNG::class,
            'scale'           => 5,
            'imageTransparent'=> false,
        ]);
        $qrcode = new \chillerlan\QRCode\QRCode($options);
        $payload = json_encode($this->getQrPayloadArray(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $qrDataUri = $qrcode->render($payload);
        return explode(',', $qrDataUri)[1];
    }
}

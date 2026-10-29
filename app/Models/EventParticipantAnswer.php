<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventParticipantAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_booking_participant_id',
        'event_question_id',
        'answer',
    ];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(EventBookingParticipant::class, 'event_booking_participant_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(EventQuestion::class, 'event_question_id');
    }

    /**
     * Return parsed answer (if JSON array or plain string)
     */
    public function getFormattedAnswerAttribute(): string
    {
        if (empty($this->answer)) {
            return '-';
        }

        $decoded = json_decode($this->answer, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return implode(', ', $decoded);
        }

        return (string) $this->answer;
    }
}

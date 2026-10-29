<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'event_routine_id',
        'user_id',
        'total_participants',
        'total_amount',
        'status',
        'ticket_type',
        'selected_routines',
        'booking_token',
        'qr_code',
        'payment_voucher',
        'rejection_note',
        'contact_email',
        'contact_phone',
        'is_present',
        'approved_by',
        'is_member',
        'cooperative_id',
        'cooperative_name',
    ];

    protected $casts = [
        'selected_routines' => 'array',
    ];

    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(CooperativeInformation::class, 'cooperative_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Status constants
    const STATUS_PENDING  = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_DRAFT    = 3;

    public static function generateUniqueToken(): string
    {
        do {
            $token = strtoupper(\Illuminate\Support\Str::random(10));
        } while (self::where('booking_token', $token)->exists());

        return $token;
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function routine(): BelongsTo
    {
        return $this->belongsTo(EventRoutine::class, 'event_routine_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(EventBookingParticipant::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_DRAFT    => 'Draft (Incomplete)',
            default               => 'Pending',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_DRAFT    => 'secondary',
            default               => 'warning',
        };
    }
}

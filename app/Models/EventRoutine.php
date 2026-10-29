<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventRoutine extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'date',
        'is_all_day',
        'start_time',
        'end_time',
        'about',
        'price',
        'non_member_price',
        'has_group_pricing',
        'group_size',
        'group_price',
        'non_member_group_price',
    ];

    public function getDisplayScheduleAttribute()
    {
        $str = '';
        if ($this->date) {
            $str .= \Carbon\Carbon::parse($this->date)->format('M d, Y') . ' | ';
        }
        if ($this->is_all_day) {
            $str .= 'All Day';
        } else if ($this->start_time && $this->end_time) {
            $str .= \Carbon\Carbon::parse($this->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($this->end_time)->format('h:i A');
        } else {
            $str .= 'TBA';
        }
        return $str;
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(EventBooking::class);
    }
}

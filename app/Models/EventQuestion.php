<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'type',
        'options',
        'is_required',
        'order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'order'       => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(EventParticipantAnswer::class);
    }

    /**
     * Get options as an array.
     */
    public function getParsedOptionsAttribute(): array
    {
        if (empty($this->options)) {
            return [];
        }

        $decoded = json_decode($this->options, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter(array_map('trim', $decoded)));
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->options))));
    }
}

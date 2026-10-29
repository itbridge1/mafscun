<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'start_date',
        'end_date',
        'registration_deadline',
        'start_date_bs',
        'end_date_bs',
        'event_price',
        'non_member_price',
        'price_type',
        'has_group_pricing',
        'group_size',
        'group_price',
        'non_member_group_price',
        'total_seats',
        'google_map',
        'remarks',
        'photo',
        'short_description',
        'description',
        'status',
        'certificate_bg_image',
        'certificate_orientation',
        'certificate_settings',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'registration_deadline' => 'date',
        'certificate_settings' => 'array',
    ];

    public function routines(): HasMany
    {
        return $this->hasMany(EventRoutine::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(EventQuestion::class)->orderBy('order')->orderBy('id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(EventBooking::class);
    }

    public function bookedSeatsCount(?int $excludeBookingId = null): int
    {
        $query = $this->bookings()
            ->where(function ($q) {
                $q->whereIn('status', [EventBooking::STATUS_PENDING, EventBooking::STATUS_APPROVED])
                  ->orWhere(function ($q2) {
                      $q2->where('status', EventBooking::STATUS_DRAFT)
                         ->where('created_at', '>=', now()->subMinutes(15));
                  });
            });
            
        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }
        
        return $query->sum('total_participants');
    }

    public function availableSeats(?int $excludeBookingId = null): int
    {
        return max(0, $this->total_seats - $this->bookedSeatsCount($excludeBookingId));
    }

    /**
     * Get a human-readable price label.
     */
    public function getPriceLabelAttribute(): string
    {
        $hasDiff = ($this->non_member_price !== null && $this->non_member_price > 0 && $this->non_member_price != $this->event_price);

        if ($this->routines && $this->routines->count() > 0) {
            $min = $this->routines->min('price') ?? 0;
            $max = $this->routines->max('price') ?? 0;
            if ($max > 0) {
                $range = ($min == $max) ? 'Rs. ' . number_format($min, 2) : 'Rs. ' . number_format($min, 2) . ' - ' . number_format($max, 2);
                $hasRoutineDiff = $this->routines->contains(function ($r) {
                    return $r->non_member_price !== null && $r->non_member_price > 0 && $r->non_member_price != $r->price;
                });
                if ($hasRoutineDiff) {
                    $nmMin = $this->routines->min('non_member_price') ?: $min;
                    $nmMax = $this->routines->max('non_member_price') ?: $max;
                    $nmRange = ($nmMin == $nmMax) ? 'Rs. ' . number_format($nmMin, 2) : 'Rs. ' . number_format($nmMin, 2) . ' - ' . number_format($nmMax, 2);
                    return $range . ' (Member) / ' . $nmRange . ' (Non-member)';
                }
                return $range;
            }
        }

        if ($hasDiff) {
            $memberText = 'Rs. ' . number_format($this->event_price, 2) . ' (Member)';
            $nonMemberText = 'Rs. ' . number_format($this->non_member_price, 2) . ' (Non-member)';
            return $memberText . ' / ' . $nonMemberText;
        }

        if ($this->has_group_pricing && $this->group_size > 0 && $this->group_price > 0) {
            return 'Rs. ' . number_format($this->event_price, 2) . ' (Single) / Rs. ' . number_format($this->group_price, 2) . ' (Group of ' . $this->group_size . ')';
        }
        
        // Legacy fallback
        if ($this->price_type === 'per_group' && $this->group_size) {
            return 'Rs. ' . number_format($this->event_price, 2) . ' / ' . $this->group_size . ' person(s)';
        }
        return 'Rs. ' . number_format($this->event_price, 2) . ' / person';
    }

    /**
     * Calculate total price for N participants with membership differentiation.
     */
    public function calculatePrice(int $participants, ?int $routineId = null, string $ticketType = 'single', bool $isMember = true): float
    {
        $memberSinglePrice = (float) $this->event_price;
        $nonMemberSinglePrice = ($this->non_member_price !== null && $this->non_member_price > 0)
            ? (float) $this->non_member_price
            : $memberSinglePrice;
        $singlePrice = $isMember ? $memberSinglePrice : $nonMemberSinglePrice;

        if ($routineId) {
            $routine = $this->routines()->find($routineId);
            if ($routine) {
                if ($ticketType === 'group' && $routine->has_group_pricing && $routine->group_size > 0) {
                    $mGrp = (float) $routine->group_price;
                    $nmGrp = ($routine->non_member_group_price !== null && $routine->non_member_group_price > 0)
                        ? (float) $routine->non_member_group_price
                        : $mGrp;
                    $grpPrice = $isMember ? $mGrp : $nmGrp;
                    return ceil($participants / $routine->group_size) * $grpPrice;
                }

                $rMem = (float) $routine->price;
                $rNonMem = ($routine->non_member_price !== null && $routine->non_member_price > 0)
                    ? (float) $routine->non_member_price
                    : $rMem;
                $rPrice = $isMember ? $rMem : $rNonMem;
                if ($rPrice > 0 || $rMem > 0) {
                    $singlePrice = $rPrice;
                }
            }
        } else {
            if ($ticketType === 'group' && $this->has_group_pricing && $this->group_size > 0) {
                $mGrp = (float) $this->group_price;
                $nmGrp = ($this->non_member_group_price !== null && $this->non_member_group_price > 0)
                    ? (float) $this->non_member_group_price
                    : $mGrp;
                $grpPrice = $isMember ? $mGrp : $nmGrp;
                return ceil($participants / $this->group_size) * $grpPrice;
            }
            if ($this->price_type === 'per_group' && $this->group_size > 0) {
                return ceil($participants / $this->group_size) * $singlePrice;
            }
        }
        return $participants * $singlePrice;
    }

    /**
     * Calculate total price for multiple routines with membership differentiation.
     */
    public function calculateTotalPrice(int $participants, array $routines, bool $isMember = true): float
    {
        if (empty($routines)) {
            return $this->calculatePrice($participants, null, 'single', $isMember);
        }

        $totalPrice = 0;
        foreach ($routines as $rt) {
            $totalPrice += $this->calculatePrice($participants, $rt['id'] ?? null, $rt['type'] ?? 'single', $isMember);
        }
        return $totalPrice;
    }

    /**
     * Check if the event registration is still open.
     */
    public function isRegistrationOpen(): bool
    {
        if ($this->registration_deadline) {
            return $this->registration_deadline->format('Y-m-d') >= \Carbon\Carbon::today()->format('Y-m-d');
        }
        
        if ($this->end_date) {
            return $this->end_date->format('Y-m-d') >= \Carbon\Carbon::today()->format('Y-m-d');
        }

        if ($this->start_date) {
            return $this->start_date->format('Y-m-d') >= \Carbon\Carbon::today()->format('Y-m-d');
        }
        
        return true;
    }

    /**
     * Parse and extract location/place name from the google map embed link or remarks fallback.
     */
    public function getLocationName(): string
    {
        $locationName = '';
        if ($this->google_map) {
            $mapData = $this->google_map;

            // If stored as a full <iframe> tag, pull out the src URL first
            if (str_contains($mapData, '<iframe')) {
                preg_match('/src=["\']([^"\']+)["\']/', $mapData, $srcMatches);
                $mapData = $srcMatches[1] ?? $mapData;
            }

            // URL-decode so %2s patterns become readable
            $mapData = urldecode($mapData);

            // Find ALL !2s segments — Google embeds store the place name as one of them
            if (preg_match_all('/!2s([^!&"\'<\s][^!&"\'<]*)/', $mapData, $allMatches)) {
                foreach ($allMatches[1] as $segment) {
                    $segment = trim($segment);
                    // Skip hex place IDs
                    if (preg_match('/^0x[0-9a-f]+/i', $segment)) continue;
                    // Skip short codes (language/country like "en", "np")
                    if (strlen($segment) <= 3) continue;
                    // Skip numeric-only values
                    if (is_numeric($segment)) continue;
                    // This looks like a real name
                    $locationName = $segment;
                    break;
                }
            }

            // Fallback: try the q= parameter (used in standard maps URLs)
            if (!$locationName && preg_match('/[?&]q=([^&"\'<]+)/', $mapData, $qMatches)) {
                $locationName = urldecode(str_replace('+', ' ', $qMatches[1]));
            }
        }

        // Final fallback: use the remarks field
        if (!$locationName && $this->remarks) {
            $locationName = $this->remarks;
        }

        return $locationName ?: 'Event Location';
    }

    /**
     * Check if the event has expired based on end_date (or start_date if no end_date is provided)
     */
    public function isEventExpired(): bool
    {
        $today = \Carbon\Carbon::today()->format('Y-m-d');
        
        // If there's an end date, use it to check expiry
        if ($this->end_date) {
            return $this->end_date->format('Y-m-d') < $today;
        }
        
        // If no end date, but there's a start date, treat as 1-day event
        if ($this->start_date) {
            return $this->start_date->format('Y-m-d') < $today;
        }
        
        return false;
    }

    /**
     * Generate a unique slug for an event based on its title.
     * Appends incremental suffix (-1, -2, etc.) if slug already exists.
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = \Illuminate\Support\Str::slug($title);
        if (empty($baseSlug)) {
            $baseSlug = 'event';
        }

        $slug = $baseSlug;
        $count = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, function ($query, $ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}

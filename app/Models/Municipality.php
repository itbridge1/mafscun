<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Municipality extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nepali_name',
        'district_name',
        'link',
        'photo',
        'district_id',
        'province_id',
        'status',
    ];

    public function province() : BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function district() : BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}

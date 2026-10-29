<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nepali_name',
        'province_id',
        'headquarter',
        'area',
        'photo',
        'autoselect',
        'status'
    ];

    public function province() : BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function municipalities() : HasMany
    {
        return $this->hasMany(Municipality::class);
    }
}

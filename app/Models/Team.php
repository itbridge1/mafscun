<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'hrm_id',
        'name',
        'slug',
        'designation',
        'photo',
        'organization',
        'phone',
        'email',
        'description',
        'status',
        'rank'
    ];

    public function hrm() : BelongsTo
    {
        return $this->belongsTo(Hrm::class,'hrm_id');
    }
}

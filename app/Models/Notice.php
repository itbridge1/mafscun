<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sub_title',
        'slug',
        'start_date',
        'end_date',
        'photo',
        'description',
        'status',
        'is_popup',
        'is_career',
        'rank'
    ];

    public function scopePopup($query)
    {
        return $query->where('is_popup',1);
    }

    public function scopeExceptCareer($query)
    {
        return $query->where('is_career', 0);
    }

    public function scopeCareer($query)
    {
        return $query->where('is_career', 1);
    }
}

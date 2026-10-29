<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CooperativeInformation extends BaseModel
{
    use HasFactory;

    protected $table = 'cooperative_informations';

    protected $fillable = [
        'cooperative_name',
        'member_no',
        'address',
        'rank',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}

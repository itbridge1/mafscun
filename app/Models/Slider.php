<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'alt_text',
        'caption',
        'photo',
        'rank',
        'status',
    ];
}

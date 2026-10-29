<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'sub_title',
        'photo',
        'description',
        'status',
        'rank',
    ];
}

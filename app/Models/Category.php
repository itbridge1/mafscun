<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'title',
        'sub_title',
        'slug',
        'photo',
        'rank',
        'description',
        'status'
    ];


}


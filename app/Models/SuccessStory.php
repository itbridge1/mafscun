<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessStory extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'organization',
        'designation',
        'membership_no',
        'photo',
        'phone',
        'email',
        'description',
        'rank',
        'status'
    ];
}

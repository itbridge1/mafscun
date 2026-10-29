<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactForm extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'phone',
        'message',
        'status'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'organization',
        'address',
        'management_feedback',
        'notification',
        'report_feedback',
        'policy_feedback',
        'other_suggestions',
    ];

   
}

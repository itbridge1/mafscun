<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'province_name',
        'province_nepali_name',
        'capital',
        'no_of_districts',
        'no_of_districts',
        'area',
        'image',
        'status'
    ];
}

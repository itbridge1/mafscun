<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'province_id',
        'district_id',
        'municipality_id',
        'total',
        'associated',
        'remarks'
    ];
}

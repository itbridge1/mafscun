<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentQr extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'details',
        'qr_image',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
